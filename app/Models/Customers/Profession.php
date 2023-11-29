<?php

namespace App\Models\Customers;

use App\Models\Users\AppLog;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['title'];
    const MORPH_TYPE = 'profession';
    
    public static function newProfession($title): self|false
    {
        $newProf = new self([
            "title" =>  $title
        ]);
        try {
            $newProf->save();
            AppLog::info("Adding profession", loggable: $newProf);
            return $newProf;
        } catch (Exception $e) {
            report($e);
            AppLog::error("Adding profession failed", desc: $e->getMessage());
            return false;
        }
    }
}
