<?php

namespace App\Models\Accounting;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;
    protected $table = 'account_types';
    protected $fillable = ['name', 'desc'];
    public $timestamps = false;

    ////static functions
    public static function newAccountType($name, $desc = null): self|false
    {
        $newType = new self([
            'name' => $name,
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
    public function editInfo($name, $desc = null): bool
    {
        $this->update([
            'name' => $name,
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
