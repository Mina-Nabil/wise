<?php

namespace App\Models\Business;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoldPolicyWatcher extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'policy_watcher';

    protected $table = 'policy_watchers';
    protected $fillable = ['user_id'];
    public $timestamps = false;

    //relations
    public function sold_policy(): BelongsTo
    {
        return $this->belongsTo(SoldPolicy::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
