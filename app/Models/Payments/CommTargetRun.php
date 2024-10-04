<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommTargetRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'percentage', 'amount', 'target_id'
    ];

}
