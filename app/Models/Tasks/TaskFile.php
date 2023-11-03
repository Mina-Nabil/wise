<?php

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskFile extends Model
{
    use HasFactory;

    protected $table = 'task_files';
    protected $fillable = ['user_id', 'file_url', 'name'];
    public $timestamps = false;

    ///relations
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
