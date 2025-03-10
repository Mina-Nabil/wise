<?php

namespace App\Models\Offers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalOfferClient extends Model
{
    use HasFactory;


    public $timestamps = false;
    protected $fillable = [
        'name', 'birth_date', 'relation'
    ];
}
