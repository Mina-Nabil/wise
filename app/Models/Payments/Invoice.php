<?php

namespace App\Models\Payments;

use App\Models\Business\SoldPolicy;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Invoice extends Model
{
    const FILES_DIRECTORY = 'comm_payments/';

    use HasFactory;

    protected $fillable = [
        'company_id',
        'created_by',
        'serial',
        'gross_total',
        'tax_total',
        'net_total'
    ];

    ///static functions
    /** @param  array $sold_policies_entries should contain an array of associated arrays [ 'id' => ? , 'amount' => ?, 'pymnt_perm' => ? ]  */
    public static function newInvoice($company_id, $serial, $gross_total, $sold_policies_entries = [])
    {
        $newInvoice = new self([
            "company_id"    =>  $company_id,
            "serial"        =>  $serial,
            "created_by"    =>  Auth::id(),
            "gross_total"   =>  $gross_total,
            "tax_total"     => ($gross_total * .05),
            "net_total"     => ($gross_total * .95),
        ]);
        try {

            DB::transaction(function () use ($newInvoice, $sold_policies_entries, $serial) {
                $newInvoice->save();
                foreach ($sold_policies_entries as $sp) {
                    /** @var SoldPolicy */
                    $soldPolicy = SoldPolicy::find($sp['id']);
                    $soldPolicy->addCompanyPayment(ClientPayment::PYMT_TYPE_BANK_TRNSFR, $sp['amount'], "added automatically from invoice#$serial", $newInvoice->id, $sp['pymnt_perm']);
                }
            });
            AppLog::info("Invoice created", loggable: $newInvoice);
            return $newInvoice;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create invoice", $e->getMessage());
            return false;
        }
    }

    public static function getNextSerial()
    {
        return (self::orderByDesc('serial')->limit(1)->first()->serial ?? 0) + 1;
    }


    ///model functions
    public function confirmInvoice()
    {
        try{

            DB::transaction(function () {
                /** @var PolicyComm */
                foreach ($this->commissions()->get() as $comm) {
                    $comm->confirmInvoice();
                }
            });
        } catch (Exception $e){
            report($e);
            return false;
        }
    }

    public function printInvoice()
    {
        $this->load('commissions', 'commissions.sold_policy', 'commissions.sold_policy.client');
        $template = IOFactory::load(resource_path('import/company_invoice.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 2;
        foreach ($this->commissions as $comm) {
            $activeSheet->getCell('A' . $i)->setValue($this->serial);
            $activeSheet->getCell('B' . $i)->setValue((new Carbon($this->created_at))->format('d-M-y'));
            $activeSheet->getCell('D' . $i)->setValue($comm->sold_policy->policy_number);
            $activeSheet->getCell('E' . $i)->setValue($comm->sold_policy->client->name);
            $activeSheet->getCell('F' . $i)->setValue((new Carbon($comm->sold_policy->issuing_date))->format('d-M-y'));
            $activeSheet->getCell('G' . $i)->setValue($comm->pymnt_perm);
            $activeSheet->getCell('I' . $i)->setValue($comm->amount);
            $activeSheet->getCell('J' . $i)->setValue($comm->amount * .05);
            $activeSheet->getCell('K' . $i)->setValue($comm->amount * .95);
            $activeSheet->insertNewRowBefore($i);
        }

        $writer = new Xlsx($newFile);
        $file_path = SoldPolicy::FILES_DIRECTORY . "invoice{$this->serial}.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    public function deleteInvoice()
    {
        try {
            DB::transaction(function () {
                /** @var PolicyComm */
                foreach ($this->commissions()->get() as $comm) {
                    $comm->deleteCommission();
                }
                $this->delete();
            });
            AppLog::info("Invoice delete");
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create invoice", $e->getMessage(), $this);
            return false;
        }
    }

    ////relations
    public function commissions(): HasMany
    {
        return $this->hasMany(CompanyCommPayment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
