<?php

namespace App\Models\Offers;

use App\Exceptions\UnauthorizedException;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class OptionField extends Model
{
    use HasFactory;
    protected $table = 'option_fields';
    protected $fillable = [
        'name',
        'value',
        'user_id',
    ];

    //model functions
    public function editField($name, $value)
    {
        try {
            if ($this->update([
                "name"  =>  $name,
                "value"  =>  $value,
            ])) {
                AppLog::info("Option Field edited", loggable: $this);
                return true;
            } else {
                AppLog::error("Option Field edit failed", desc: "Failed to edit option field", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Option field edit failed", desc: $e->getMessage(), loggable: $this);
            return true;
        }
    }

    public function delete()
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('delete', $this)) throw new UnauthorizedException();
        try {
            $this->load('option');
            $tmpOption = $this->option;
            if (parent::delete()) {
                AppLog::info("Option Field deleted", loggable: $tmpOption);
                return true;
            } else {
                AppLog::error("Option Field deletetion failed", loggable: $this);
                return false;
            }
        } catch (Exception $e) {
            AppLog::info("Field deletetion failed", loggable: $this, desc: $e->getMessage());
            report($e);
            return false;
        }
    }

    ///relations
    public function option(): BelongsTo
    {
        return $this->belongsTo(OfferOption::class);
    }
}
