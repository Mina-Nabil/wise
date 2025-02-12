<?php

namespace App\Models\Cars;

use App\Exceptions\UnauthorizedException;
use App\Models\Base\Country;
use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Brand extends Model
{
    const MORPH_TYPE = 'brand';

    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'country_id'];
    public $timestamps = false;

    ///static functions 
    public static function newBrand($name, $country_id)
    {
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('create', self::class)) throw new UnauthorizedException();

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
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('update', $this)) throw new UnauthorizedException();

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

    public static function deleteBrand($brandId)
    {

        $brand = self::findOrFail($brandId);
        /** @var User */
        $loggedInUser = Auth::user();
        if (!$loggedInUser->can('delete', $brand)) throw new UnauthorizedException();

        try {
            $brand->delete();

            return true; // Deletion successful
        } catch (Exception $e) {
            report($e);
            AppLog::error("Error deleting brand with ID {$brandId}: {$e->getMessage()}");
            return false;
        }
    }

    ////scopes
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
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
