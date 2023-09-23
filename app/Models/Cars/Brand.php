<?php

namespace App\Models\Cars;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'country_id'];
    protected $timestamps = false;

    ///static functions 
    public static function newBrand($name, $country_id)
    {
        try {
            $newBrand = new self([
                "name"          =>  $name,
                "country_id"    =>  $country_id
            ]);
            $newBrand->save();
            AppLog::info('New Brand created', "Brand $name added");
            return $newBrand;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Saving Brand error", $e->getMessage());
            return false;
        }
    }

    ///model functions 
    public function editInfo($name, $country_id)
    {
        try {
            if ($this->update([
                "name"          =>  $name,
                "country_id"    =>  $country_id
            ])) {
                AppLog::info('Brand updated', "Brand $name updated");
                return true;
            } else return false;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Saving Brand error", $e->getMessage());
            return false;
        }
    }


    /////relations
    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
