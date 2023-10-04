<?php

namespace App\Models\Insurance;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $table = 'insurance_companies';
    protected $fillable = [
        'name',  //company name
        'note'  //note about the company (nullable)
    ];

    ///static functions
    public static function newCompany($name, $note = null): self|false
    {
        $newCompany = new self([
            "name"  =>  $name,
            "note"  =>  $note
        ]);

        try {
            $newCompany->save();
            AppLog::info('New Company added', "Company $name ($newCompany->id) added successfully");
            return $newCompany;
        } catch (Exception $e) {
            AppLog::error("Can't add company", $e->getMessage());
            report($e);
            return false;
        }
    }

    ///model functions
    public function editInfo($name, $note = null): bool
    {
        try {
            $this->update([
                "name"  =>  $name,
                "note"  =>  $note
            ]);

            AppLog::info('Company data update', "Company $name ($this->id) updated successfully");
            return true;
        } catch (Exception $e) {
            AppLog::error("Can't edit company", $e->getMessage());
            report($e);
            return false;
        }
    }

    public function addEmail(
        $type,
        $email,
        $is_primary,
        $first_name = null,
        $last_name = null,
        $note = null
    ) {
        try {
            $email = $this->emails()->updateOrCreate([
                "type"  =>  $type,
                "email" =>  $email
            ], [
                "contact_first_name"    =>  $first_name,
                "contact_last_name"    =>  $last_name,
                "is_primary"    =>  $last_name,
                "note"      =>  $note
            ]);
            if ($is_primary) {
                $this->emails()->whereNot("id", $email->id)->update(
                    ["is_primary"    =>  0]
                );
            }
        } catch (Exception $e) {
            report($e);
            AppLog::error("Can't set email", $e->getMessage());
            return false;
        }
    }

    //scopes
    public function scopeSearchBy($query, $text)
    {
        return $query->where('insurance_companies.name','LIKE', "%$text%");
    }

    ///relations
    public function emails(): HasMany
    {
        return $this->hasMany(CompanyEmail::class);
    }
    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }
}
