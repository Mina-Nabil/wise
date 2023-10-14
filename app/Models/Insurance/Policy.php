<?php

namespace App\Models\Insurance;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    CONST MORPH_TYPE = 'policy';

    use HasFactory, SoftDeletes;

    CONST BUSINESS_MOTOR = 'Motor';
    CONST BUSINESS_HEALTH = 'Health';
    CONST BUSINESS_LIFE = 'Life';
    CONST BUSINESS_PROPERTY = 'Property';
    CONST BUSINESS_CARGO = 'Cargo';

    CONST LINES_OF_BUSINESS = [
        self::BUSINESS_MOTOR,
        self::BUSINESS_HEALTH,
        self::BUSINESS_LIFE,
        self::BUSINESS_PROPERTY,
        self::BUSINESS_CARGO,
    ];

    protected $table = 'policies';
    protected $fillable = [
        'company_id',
        'name', //policy as named by the insurance company
        'business', //line of business - enum - motor,cargo..
        'note' //extra note for users - nullable
    ];

    ///static functions
    public static function newPolicy($company_id, $name, $business, $note = null)
    {
        $newPolicy = new self([
            "company_id" =>  $company_id,
            "name"      =>  $name,
            "business"  =>  $business,
            "note"      =>  $note
        ]);
        try {
            $newPolicy->save();
            AppLog::info('New Policy added', "Policy $name ($newPolicy->id) added successfully");
            return $newPolicy;
        } catch (Exception $e) {
            AppLog::error("Can't add policy", $e->getMessage());
            report($e);
            return false;
        }
    }

    ///model functions
    public function editInfo($name, $business, $note = null)
    {
        $this->update([
            "name"      =>  $name,
            "business"  =>  $business,
            "note"      =>  $note
        ]);
        try {
            $this->save();
            AppLog::info('Policy update', "Policy $name ($this->id) updated successfully");
            return true;
        } catch (Exception $e) {
            AppLog::error("Can't edit policy", $e->getMessage());
            report($e);
            return false;
        }
    }

    public function addCondition(
        $scope,
        $operator,
        $value,
        $rate,
        $note
    ): false|PolicyCondition {
        try {
            $order = $this->conditions()->count() + 1;
            $condition = $this->conditions()->create([
                "scope" =>  $scope,
                "operator" =>  $operator,
                "value" =>  $value,
                "order" =>  $order,
                "rate" =>  $rate,
                "note" =>  $note,
            ]);
            AppLog::info('Condition Added', "New condition added for $this->name");
            return $condition;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Adding condition failed', $e->getMessage());
            return false;
        }
    }

    //scopes
    public function scopeTableData($query)
    {
        $query->join('insurance_companies', 'insurance_companies.id', '=', 'policies.company_id')
            ->select('policies.*');
    }

    /**
     * must use table data first
     **/
    public function scopeSearchBy($query, $text)
    {
        return $query->where(function ($q) use ($text) {
            $q->where('policies.name', 'LIKE', "%$text%")
                ->orWhere('insurance_companies.name', 'LIKE', "%$text%");
        });
    }

    public function scopeWithConditions($query)
    {
        $query->with('conditions');
    }

    public function scopeWithCompany($query)
    {
        $query->with('company');
    }

    ///relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(PolicyCondition::class);
    }
}
