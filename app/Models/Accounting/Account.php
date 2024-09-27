<?php

namespace App\Models\Accounting;

use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Account extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'account';

    protected $table = 'accounts';
    protected $fillable = [
        'code',
        'name',
        'desc',
        'nature',
        'main_account_id',
        'parent_account_id', //list of accounts from the same main account
        'limit',
        'balance',
        'foreign_balance'
    ];

    const NATURE_CREDIT = 'credit';
    const NATURE_DEBIT = 'debit';
    const NATURES = [
        self::NATURE_DEBIT,
        self::NATURE_CREDIT,
    ];


    ////static functions
    public static function getEntries($account_id, Carbon $from, Carbon $to)
    {
        return DB::table('journal_entries')
            ->join('entry_accounts', 'entry_accounts.journal_entry_id', '=', 'journal_entries.id')
            ->join('entry_titles', 'entry_titles.id', '=', 'journal_entries.entry_title_id')
            ->join('users', 'users.id', '=', 'journal_entries.user_id')
            ->where('entry_accounts.account_id', $account_id)
            ->whereBetween('journal_entries.created_at', [
                $from->format('Y-m-d H:i'),
                $to->format('Y-m-d H:i'),
            ])
            ->groupBy('journal_entries.id')

            ->select('journal_entries.*', 'account_foreign_balance', 'account_balance', 'currency_rate', 'doc_url', 'users.username', 'entry_titles.name')
            ->selectRaw('IF(entry_accounts.nature = "debit" , entry_accounts.amount , 0 ) as debit_amount')
            ->selectRaw('IF(entry_accounts.nature = "credit" , entry_accounts.amount , 0 ) as credit_amount')
            ->selectRaw('IF(entry_accounts.nature = "debit" , entry_accounts.currency_amount , 0 ) as debit_foreign_amount')
            ->selectRaw('IF(entry_accounts.nature = "credit" , entry_accounts.currency_amount , 0 ) as credit_foreign_amount')
            ->get();
    }

    public static function newAccount($code, $name, $nature, $main_account_id, $parent_account_id = null, $desc = null, $is_seeding = false): self|false
    {

        /** @var User */
        $loggedInUser = Auth::user();
        if (!$is_seeding && !$loggedInUser->can('create', self::class)) return false;

        $newAccount = new self([
            "code"      =>  $code,
            "name"      =>  $name,
            "nature"    =>  $nature,
            "parent_account_id"  =>  $parent_account_id,
            "main_account_id"  =>  $main_account_id,
            "desc"      =>  $desc,
            "balance"   =>  0,
            "foreign_balance"   =>  0,
        ]);
        try {
            $newAccount->save();
            AppLog::info("Created account", loggable: $newAccount);
            return $newAccount;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create account", desc: $e->getMessage());
            return false;
        }
    }

    ////model functions
    public function downloadAccountDetails(Carbon $from, Carbon $to)
    {
        $debit_entries = $this->debit_entries()->with('entry_title')->between($from, $to)->get();
        $credit_entries = $this->credit_entries()->with('entry_title')->between($from, $to)->get();
        $all_entries = $debit_entries->merge($credit_entries);
        $template = IOFactory::load(resource_path('import/accounting_sheets.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getSheet(0);

        $activeSheet->getCell('C3')->setValue("تحليلى	" . $this->name);

        $i = 8;
        foreach ($all_entries as $e) {
            $activeSheet->getCell('C' . $i)->setValue($e->id);
            $activeSheet->getCell('D' . $i)->setValue(Carbon::parse($e->created_at)->format('d / M / Y'));

            $activeSheet->getCell('E' . $i)->setValue($e->entry_title->name);

            if ($e->debit_id) {
                $activeSheet->getCell('E' . $i)->setValue($e->amount);
                $activeSheet->getCell('H' . $i)->setValue($e->debit_balance);
            } else {
                $activeSheet->getCell('F' . $i)->setValue($e->amount);
                $activeSheet->getCell('H' . $i)->setValue($e->credit_balance);
            }
            $activeSheet->insertNewRowBefore($i);
        }

        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "account_balance.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }


    /** returns new balance after update */
    public function updateBalance($amount, $type, $is_seeding = false)
    {
        if (!$is_seeding) {
            /** @var User */
            $loggedInUser = Auth::user();
            if (!$loggedInUser->can('update', $this)) return false;
        }
        if ($this->nature != $type) $amount = -1 * $amount;

        $this->balance = $this->balance + $amount;
        try {
            $this->save();
            return $this->balance;
        } catch (Exception $e) {
            report($e);
            return 0;
        }
    }

    public function updateForeignBalance($amount, $type, $is_seeding = false)
    {
        if (!$is_seeding) {
            /** @var User */
            $loggedInUser = Auth::user();
            if (!$loggedInUser->can('update', $this)) return false;
        }
        if ($this->nature != $type) $amount = -1 * $amount;

        $this->foreign_balance = $this->foreign_balance + $amount;
        try {
            $this->save();
            return $this->foreign_balance;
        } catch (Exception $e) {
            report($e);
            return 0;
        }
    }

    public function needsApproval($amount)
    {
        return $this->limit <= $amount;
    }

    public function editInfo($code, $name, $nature, $main_account_id, $parent_account_id = null, $desc = null): bool
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) return false;

        try {
            $this->update([
                "code"  =>  $code,
                "name"  =>  $name,
                "nature"  =>  $nature,
                "main_account_id"  =>  $main_account_id,
                "parent_account_id"  =>  $parent_account_id,
                "desc"  =>  $desc,
            ]);
            AppLog::info("Updating account", loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit account", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    ///attributes
    public function getFullCodeAttribute()
    {
        $this->loadMissing('parent_account');
        $this->loadMissing('main_account');

        if (!$this->parent_account) return $this->main_account->code . '-' . $this->code;
        else return $this->parent_account->full_code . '-' . $this->code;
    }

    public function getIsForeignAttribute()
    {
        $this->default_currency !== JournalEntry::CURRENCY_EGP;
    }

    public function getHasChildrenAttribute()
    {
        $this->loadCount('children_accounts');
        return $this->children_accounts_count > 0;
    }

    public function getIsTopParentAttribute()
    {
        return is_null($this->parent_account_id);
    }

    ///scopes
    public function scopeByNature($query, $nature)
    {
        return $query->where('nature', $nature);
    }
    public function scopeSearchBy($query, $text)
    {
        return $query->where('name',  "LIKE", "%$text%");
    }
    public function scopeByMainAccount($query, $main_account_id)
    {
        return $query->where('main_account_id ', $main_account_id);
    }

    public function scopeParentAccounts($query)
    {
        return $query->whereNull('parent_account_id');
    }

    ////relations
    public function credit_entries()
    {
        return $this->hasMany(JournalEntry::class)->where();
    }

    public function debit_entries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function main_account()
    {
        return $this->belongsTo(MainAccount::class);
    }

    public function parent_account()
    {
        return $this->belongsTo(self::class, 'parent_account_id');
    }

    public function children_accounts()
    {
        return $this->hasMany(self::class, 'parent_account_id');
    }
}
