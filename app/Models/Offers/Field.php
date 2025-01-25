<?php

namespace App\Models\Offers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
    
    protected $table = 'offer_fields';
    public $timestamps = false;

    protected $fillable = [
        'field',
        'value',
    ];
}
