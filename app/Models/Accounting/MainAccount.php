<?php

namespace App\Models\Accounting;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MainAccount extends Model
{
    use HasFactory;
    protected $table = 'main_accounts';
    protected $fillable = ['name', 'desc', 'type'];
    public $timestamps = false;


    const TYPE_EXPENSE = 'expense';
    const TYPE_REVENUE = 'revenue';
    const TYPE_ASSET = 'asset';
    const TYPE_LIABILITY = 'liability';
    const TYPES = [
        self::TYPE_EXPENSE,
        self::TYPE_REVENUE,
        self::TYPE_ASSET,
        self::TYPE_LIABILITY,
    ];

    ////static functions
    public static function newMainAccount($name, $type, $desc = null, $is_seeding = false): self|false
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$is_seeding && !$loggedInUser->can('create', Account::class)) return false;

        $newType = new self([
            'name' => $name,
            'type' => $type,
            'desc' => $desc,
        ]);
        try {
            $newType->save();
            AppLog::info('Created account type', loggable: $newType);
            return $newType;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't create account type", desc: $e->getMessage());
            return false;
        }
    }

    ////model functions
    public function editInfo($name, $type, $desc = null): bool
    {
        $this->update([
            'name' => $name,
            'type' => $type,
            'desc' => $desc,
        ]);
        try {
            AppLog::info('Updating account type', loggable: $this);
            return $this->save();
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't edit account type", desc: $e->getMessage(), loggable: $this);
            return false;
        }
    }

    public function scopeSearch($query, $term)
    {
        return $query
            ->where('name', 'LIKE', '%' . $term . '%')
            ->orWhere('desc', 'LIKE', '%' . $term . '%')
            ->orderBy('id', 'desc'); // Order by the newest added
    }

    ////relations
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
