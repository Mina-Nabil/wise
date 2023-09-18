<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Note extends Model
{
    use HasFactory;

    protected $table = 'user_notes';
    protected $fillable = [
        'title', 'desc', 'user_id'
    ];

    ///relations
    public function notable() : MorphTo
    {
        return $this->morphTo();
    }
}
