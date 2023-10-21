<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskComment extends Model
{
    use HasFactory;

    protected $table = 'task_comments';
    protected $fillable = [
        "comment", "task_id", "user_id"
    ];

    //relations
    public function task() : BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
