<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
    
    protected $table = 'sold_policy_fields';
    public $timestamps = false;

    protected $fillable = [
        'field',
        'value',
        'is_mandatory'
    ];
}
