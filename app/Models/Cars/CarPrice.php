<?php

namespace App\Models\Cars;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarPrice extends Model
{
    use HasFactory;

    protected $table = 'car_prices';
    protected $fillable = [
        'car_id','model_year', 'price', 'desc'
    ];

    //relations
    public function car() : BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
