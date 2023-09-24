<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
use HasFactory;
    protected $fillable = [
        'sender_id', 'title', 'route', 'message'
    ];

    //mutator
    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => url($value),
        );
    }

    //relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
