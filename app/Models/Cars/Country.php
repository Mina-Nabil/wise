<?php

namespace App\Models\Cars;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    CONST MORPH_TYPE = 'country';

    use HasFactory;

    protected $fillable = ['name'];
    public $timestamps = false;

    ////static function 
    public static function newCountry($name)
    {
        $newCountry = new self([
            "name"  =>  $name
        ]);
        try {
            $newCountry->save();
            return $newCountry;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///scopes 
    public function scopeSortByName(Builder $query)
    {
        return $query->orderBy('name');
    }

    ///relations
    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}
