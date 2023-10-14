<?php

namespace App\Models\Insurance;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyEmail extends Model
{
    CONST MORPH_TYPE = 'company_email';

    use HasFactory;

    CONST TYPE_INFO = 'Info';
    CONST TYPE_FINANCE = 'Finance';
    CONST TYPE_OPERATIONS = 'Operations';
    CONST TYPE_SALES = 'Sales';
    CONST TYPE_SUPPORT = 'Support';

    CONST TYPES = [
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
    public function toggleEmail(): bool
    {
        $this->is_primary = $this->is_primary ? 0 : 1;
        try {
            return $this->save();
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
