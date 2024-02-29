<?php

namespace App\Models\Offers;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferWatcher extends Model
{
    use HasFactory;
    const MORPH_TYPE = 'offer_watcher';

    protected $table = 'offer_watchers';
    protected $fillable = ['user_id'];
    public $timestamps = false;

    //relations
    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
