<?php

namespace App\Models\Customers;

use App\Models\Corporates\Corporate;
use App\Models\Users\AppLog;
use App\Models\Users\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Followup extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'followup';

    const STATUS_NEW = 'new';
    const STATUS_CALLED = 'called';
    const STATUS_CANCELLED = 'canceled';
    const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_CALLED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'title',
        'status',
        'call_time',
        'action_time',
        'desc',
        'caller_note',
        'creator_id',
        'is_meeting',
        'line_of_business'
    ];

    ///model functions
    public function editInfo($title, $call_time = null, $desc = null, $is_meeting = false, $line_of_business = null)
    {
        try {
            $res = $this->update([
                "title"             =>  $title,
                "call_time"         =>  $call_time,
                "is_meeting"        =>  $is_meeting,
                "line_of_business"  =>  $line_of_business,
                "desc"              =>  $desc
            ]);
            AppLog::info("Follow-up updated", loggable: $this);
            return $res;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit followup", desc: $e->getMessage());
            return false;
        }
    }

    public function setAsCalled($note = null)
    {
        if ($this->status !== self::STATUS_NEW) return false;
        try {
            $res = $this->update([
                "action_time"   =>  Carbon::now()->format('Y-m-d H:i:s'),
                "status"        =>  self::STATUS_CALLED,
                "caller_note"   =>  $note
            ]);
            AppLog::info("Follow-up done", loggable: $this);
            return $res;
        } catch (Exception $e) {
            AppLog::error("Can't set followup done", $e->getMessage(), $this);
            report($e);
            return false;
        }
    }

    public function setAsCancelled($note = null)
    {
        if ($this->status !== self::STATUS_NEW) return false;
        try {
            $res = $this->update([
                "action_time"   =>  Carbon::now()->format('Y-m-d H:i:s'),
                "status"        =>  self::STATUS_CANCELLED,
                "caller_note"   =>  $note
            ]);
            AppLog::info("Follow-up cancelled", loggable: $this);
            return $res;
        } catch (Exception $e) {
            AppLog::error("Can't cancel followup", $e->getMessage(), $this);
            report($e);
            return false;
        }
    }

    public function addComment($comment)
    {
        try {
            $this->comments()->create([
                "user_id"   =>  Auth::id(),
                "comment"   =>  $comment
            ]);
            AppLog::info("Follow up comment added", loggable: $this);
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create follow up comment", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }
    ///scopes
    public function scopeUserData($query, $searchText = null, $upcoming_only = false, $mineOnly = false)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        $query->select('followups.*')
            ->join('users', "followups.creator_id", '=', 'users.id');

        if ($loggedInUser->type !== User::TYPE_ADMIN || $loggedInUser->id != 12 || $mineOnly) {
            $query->where(function ($q) use ($loggedInUser) {
                $q->where('users.manager_id', $loggedInUser->id)
                    ->orwhere('users.id', $loggedInUser->id);
            });
        }

        $query->when($searchText, function ($q, $v) {
            $q->leftjoin('corporates', function ($j) {
                $j->on('followups.called_id', '=', 'corporates.id')
                    ->where('followups.called_type', Corporate::MORPH_TYPE);
            })->leftjoin('customers', function ($j) {
                $j->on('followups.called_id', '=', 'customers.id')
                    ->where('followups.called_type', Customer::MORPH_TYPE);
            })->groupBy('followups.id');

            $splittedText = explode(' ', $v);

            foreach ($splittedText as $tmp) {
                $q->where(function ($qq) use ($tmp) {
                    $qq->where('followups.title', 'LIKE', "%$tmp%")
                        ->orwhere('customers.first_name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.last_name', 'LIKE', "%$tmp%")
                        ->orwhere('corporates.name', 'LIKE', "%$tmp%")
                        ->orwhere('customers.email', 'LIKE', "%$tmp%")
                        ->orwhere('corporates.email', 'LIKE', "%$tmp%");
                });
            }
        })->when($upcoming_only, function ($q) {
            $now = new Carbon();
            $q->whereBetween('call_time', [
                $now->format('Y-m-01'),
                $now->addMonth()->format('Y-m-t')
            ]);
        });

        return $query->latest();
    }

    public function scopeReport($query, Carbon $due_from = null, Carbon $due_to = null, Carbon $action_from = null, Carbon $action_to = null, string $sales_id = null, string $client_type = null, string $client_id = null, bool $is_meeting = null, string $line_of_business = null)
    {
        $query->when($due_from, function ($q, $v) {
            $q->where('call_time', ">=", $v->format('Y-m-d'));
        })->when($due_to, function ($q, $v) {
            $q->where('call_time', "<=", $v->format('Y-m-d'));
        })->when($action_from, function ($q, $v) {
            $q->where('action_time', ">=", $v->format('Y-m-d'));
        })->when($action_to, function ($q, $v) {
            $q->where('action_time', "<=", $v->format('Y-m-d'));
        })->when($sales_id, function ($q, $v) {
            $q->where('creator_id', ">=", $v);
        })->when($client_type && $client_id, function ($q) use ($client_type, $client_id) {
            $q->where('called_type', $client_type)->where('called_id', $client_id);
        })->when($is_meeting !== null, function ($q) use ($is_meeting) {
            $q->where('is_meeting', $is_meeting);
        })->when($line_of_business, function ($q, $v) {
            $q->where('line_of_business', "=", $v);
        })->with('called');
    }

    ///static functions
    public static function exportReport(Carbon $due_from = null, Carbon $due_to = null, Carbon $action_from = null, Carbon $action_to = null, string $sales_id = null, string $client_type = null, string $client_id = null, bool $is_meeting = null, string $line_of_business = null)
    {
        $followups = self::report($due_from, $due_to, $action_from, $action_to, $sales_id, $client_type, $client_id, $is_meeting, $line_of_business)->get();
        $template = IOFactory::load(resource_path('import/followups_report.xlsx'));
        if (!$template) {
            throw new Exception('Failed to read template file');
        }
        $newFile = $template->copy();
        $activeSheet = $newFile->getActiveSheet();

        $i = 2;
        foreach ($followups as $followup) {
            $activeSheet->getCell('A' . $i)->setValue($followup->creator->username);
            $activeSheet->getCell('B' . $i)->setValue($followup->called->name);
            $activeSheet->getCell('C' . $i)->setValue($followup->line_of_business);
            $activeSheet->getCell('D' . $i)->setValue($followup->title);
            $activeSheet->getCell('E' . $i)->setValue($followup->status);
            $activeSheet->getCell('F' . $i)->setValue(Carbon::parse($followup->call_time)->format('Y-m-d'));
            $activeSheet->getCell('G' . $i)->setValue(Carbon::parse($followup->action_time)->format('Y-m-d'));
            $activeSheet->getCell('H' . $i)->setValue($followup->caller_note);
            $activeSheet->insertNewRowBefore($i);
        }

        $writer = new Xlsx($newFile);
        $file_path = Customer::FILES_DIRECTORY . "followups_export.xlsx";
        $public_file_path = storage_path($file_path);
        $writer->save($public_file_path);

        return response()->download($public_file_path)->deleteFileAfterSend(true);
    }

    ///relations
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function comments(): HasMany
    {
        return $this->hasMany(FollowupComment::class);
    }
    public function called(): MorphTo
    {
        return $this->morphTo();
    }
}
