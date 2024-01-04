<?php

namespace App\Models\Base;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    CONST MORPH_TYPE = 'city';

    use HasFactory;

    protected $fillable = ['name'];
    public $timestamps = false;

    ////static function 
    public static function newCity($name)
    {
        $newCity = new self([
            "name"  =>  $name
        ]);
        try {
            $newCity->save();
            return $newCity;
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
}
