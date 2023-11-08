<?php

namespace App\Models\Insurance;

use App\Exceptions\UnauthorizedException;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class CompanyEmail extends Model
{
    const MORPH_TYPE = 'company_email';

    use HasFactory;

    const TYPE_INFO = 'Info';
    const TYPE_FINANCE = 'Finance';
    const TYPE_OPERATIONS = 'Operations';
    const TYPE_SALES = 'Sales';
    const TYPE_SUPPORT = 'Support';

    const TYPES = [
        self::TYPE_INFO,
        self::TYPE_FINANCE,
        self::TYPE_OPERATIONS,
        self::TYPE_SALES,
        self::TYPE_SUPPORT,
    ];

    protected $table = 'insurance_companies_emails';
    protected $fillable = [
        "type", //one of emails types
        "email", //email itself
        "is_primary", //is email primary for the company?
        "contact_first_name", //email user first name -- nullable
        "contact_last_name", //email user last name -- nullable
        "note" //extra note for users -- nullable
    ];

    //model functions
    public function editInfo(
        $type,
        $email,
        $is_primary,
        $first_name = null,
        $last_name = null,
        $note = null
    ): bool {
        try {
            /** @var User */
            $loggedInUser = Auth::user();
            $this->loadMissing('company');
            if (!$loggedInUser->can('update', $this->company)) throw new UnauthorizedException();
            $this->update([
                "type"  => $type,
                "email"  => $email,
                "contact_first_name"  => $first_name,
                "contact_last_name"  => $last_name,
                "is_primary"  => $is_primary,
                "note"  => $note,
            ]);
            if ($is_primary) {
                self::where('company_id', $this->company_id)->whereNot("id", $this->id)->update(
                    ["is_primary"    =>  0]
                );
            }
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Toggle Email failed", $e->getMessage());
            return false;
        }
    }

    ///relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
