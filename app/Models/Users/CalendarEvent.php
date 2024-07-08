<?php

namespace App\Models\Users;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class CalendarEvent extends Model
{
    use HasFactory;
    protected $table = 'calendar_events';
    protected $fillable = [
        'title',
        'start_time',
        'end_time',
        'location',
        'note',
        'all_day',
        'all_users',
    ];

    ///model functions
    /** @param array $users_array each array item shall be an array of 'tag' , 'user_id' & 'guest_name'  */
    public function setUsers($users_array)
    {
        try {
            $this->event_users()->delete();
            $this->event_users()->createMany($users_array);
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function editInfo(
        $title,
        Carbon $start_time,
        Carbon $end_time,
        $all_day = false,
        $all_user = false,
        $location = null,
        $note = null,
    ) {
        $this->update([
            'title' =>  $title,
            'start_time' =>  $start_time,
            'end_time' =>  $end_time,
            'all_day' =>  $all_day,
            'all_user' =>  $all_user,
            'location' =>  $location,
            'note' =>  $note
        ]);

        try {
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///static functions
    /** @param array $users each array item shall be an array of 'tag', 'user_id' & 'guest_name'  */
    public static function newEvent(
        $title,
        Carbon $start_time,
        Carbon $end_time,
        $all_day = false,
        $all_user = false,
        $location = null,
        $note = null,
        $users = []
    ) {
        $newEvent = new self([
            'title' =>  $title,
            'start_time' =>  $start_time,
            'end_time' =>  $end_time,
            'all_day' =>  $all_day,
            'all_user' =>  $all_user,
            'location' =>  $location,
            'note' =>  $note
        ]);

        try {
            $newEvent->save();
            $newEvent->setUsers($users);
            return $newEvent;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///scopes
    public function scopeUserdata($query, Carbon $from = null, Carbon $to = null)
    {
        $from = $from ?? Carbon::now();
        $to = $to ?? Carbon::now()->addMonths(2);

        return $query->select('events_users.id')
            ->join('events_users', 'events_users.user_id', '=', 'users.id')
            ->where('events_users.user_id', Auth::id())
            ->whereBetween('start_time', [
                $from->format('Y-m-d'),
                $to->format('Y-m-d')
            ])
            ->groupBy('events_users.id');
    }

    //attributes
    public function getEventUsersNamesAttribute()
    {
        $this->loadMissing('event_users');
        $names = '';

        foreach($this->event_users as $u){
            $names .= ($u->title . ', ');
        }

        return $names;
    }

    ///relations
    public function event_users(): HasMany
    {
        return $this->hasMany(CalendarEventUser::class);
    }
}
