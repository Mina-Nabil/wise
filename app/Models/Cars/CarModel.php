<?php

namespace App\Models\Cars;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'brand_id'];
    public $timestamps = false;

    ///static functions 
    public static function newCarModel($name, $brand_id)
    {
        try {
            $newCarModel = new self([
                "name"      =>  $name,
                "brand_id"  =>  $brand_id
            ]);
            $newCarModel->save();
            return $newCarModel;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///model functions 
    public function editInfo($name, $brand_id)
    {
        try {
            return $this->update([
                "name"      =>  $name,
                "brand_id"  =>  $brand_id
            ]);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function scopeGetBrandModels($query, $brand_id)
    {
        return $query->where('brand_id', $brand_id);
    }

    public static function deleteModel($ModelId)
    {
        try {
            $model = self::findOrFail($ModelId);
            $model->delete();

            return true; // Deletion successful
        } catch (Exception $e) {
            report($e);
            AppLog::error("Error deleting Model with ID {$ModelId}: {$e->getMessage()}");
            return false;
        }
    }


    /////relations
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}