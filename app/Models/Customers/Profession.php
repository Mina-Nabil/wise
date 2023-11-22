<?php

namespace App\Models\Customers;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public static function newProfession($title): self|false
    {
        $newProf = new self([
            "title" =>  $title
        ]);
        try {
            $newProf->save();
            return $newProf;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
}
