<?php

namespace App\Models\Users;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class AppLog extends Model
{
    use HasFactory;

    const LEVEL_INFO = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';

    const LEVELS = [
        self::LEVEL_INFO, self::LEVEL_WARNING, self::LEVEL_ERROR
    ];

    protected $table = 'app_logs';
    protected $fillable = [
        'title', 'desc', 'level', 'user_id'
    ];

    //static functions
    public static function info($title, $desc = null)
    {
        $user = Auth::user();
        self::addLog(self::LEVEL_INFO, $title, $desc, $user?->id);
    }
    
    public static function warning($title, $desc = null)
    {
        $user = Auth::user();
        self::addLog(self::LEVEL_WARNING, $title, $desc, $user?->id);
    }
    
    public static function error($title, $desc = null)
    {
        $user = Auth::user();
        self::addLog(self::LEVEL_ERROR, $title, $desc, $user?->id);
    }

    private static function addLog($level, $title, $desc, $user_id = null)
    {
        $newLog = new self([
            "title"     =>  $title,
            "level"     =>  $level,
            "desc"      =>  $desc,
            "user_id"   =>  $user_id
        ]);
        try {
            $newLog->save();
        } catch (Exception $e) {
            report($e);
        }
    }


    public static function byDates(Carbon $start_date = null, Carbon $end_date = null)
    {
        return self::fromTo($start_date, $end_date)->get();
    }

    //scopes
    public function scopeFromTo(Builder $query, Carbon $start_date = null, Carbon $end_date = null)
    {
        $query->when($start_date, function ($query, $start_date) {
            $query->where('created_at', '>=', $start_date->format('Y-m-d H:i:s'));
        })->when($end_date, function ($query, $end_date) {
            $query->where('created_at', '<=', $end_date->format('Y-m-d H:i:s'));
        });
    }

    ///relations
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}