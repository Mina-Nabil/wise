<?php

namespace App\Models\Base;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    CONST MORPH_TYPE = 'area';

    use HasFactory;

    protected $fillable = ['name'];
    public $timestamps = false;

    ////static function 
    public static function newArea($name)
    {
        $newArea = new self([
            "name"  =>  $name
        ]);
        try {
            $newArea->save();
            return $newArea;
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
