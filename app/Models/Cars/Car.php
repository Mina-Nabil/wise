<?php

namespace App\Models\Cars;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Car extends Model
{
    use HasFactory, SoftDeletes;
    protected $timestamps = false;
    protected $fillable = [
        'car_model_id', 'category', 'desc'
    ];

    ///static functions
    public static function newCar(int $car_model_id, string $category, string $desc = null)
    {
        $newCar = new self([
            "car_model_id"  =>  $car_model_id,
            "category"      =>  $category,
            "desc"          =>  $desc,
        ]);
        try {
            $newCar->save();
            return $newCar;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///model functions
    public function editInfo(int $car_model_id, string $category, string $desc = null)
    {
        $this->update([
            "car_model_id"  =>  $car_model_id,
            "category"      =>  $category,
            "desc"          =>  $desc,
        ]);
        try {
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * @param array $prices array of 'model_year', 'price' and 'desc' values
     */
    public function setPrices(array $prices)
    {
        try {
            DB::transaction(function () use ($prices) {
                $this->car_prices()->delete();
                $this->car_prices()->createMany($prices);
                AppLog::info('Prices update', "Car($this->id) price updated");
            });
            return true;
        } catch (Exception $e) {
            report($e);
            AppLog::error('Prices update failed', $e->getMessage());
            return false;
        }
    }

    //scopes
    public function scopeWithPrices($query)
    {
        return $query->with('car_prices');
    }


    ///relations
    public function car_prices(): HasMany
    {
        return $this->hasMany(CarPrice::class);
    }
}
