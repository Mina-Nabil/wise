<?php

namespace App\Models\Tasks;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskTempAssignee extends Model
{
    use HasFactory;

    protected $table = 'task_temp_assignee';
    protected $fillable = ['user_id', 'status', 'end_date', 'note'];

    //relations
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
