<?php

namespace App\Http\Livewire;

use App\Models\Customers\Followup;
use App\Models\Event;
use App\Models\Offers\Offer;
use App\Models\Payments\ClientPayment;
use App\Models\Tasks\Task;
use App\Models\Users\CalendarEvent;
use App\Models\Users\User;
use App\Models\Users\CalendarEventUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use App\Traits\AlertFrontEnd;
use Illuminate\Support\Facades\Log;

class Calendar extends Component
{
    use AlertFrontEnd;

    public $newEventSection = false;

    public $title;
    public $start_time;
    public $end_time;
    public $all_day = false;
    public $all_user = false;
    public $location;
    public $note;
    public $users_array = [];

    public function addEvent()
    {
        // Validate the input data
        $this->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'all_day' => 'nullable|boolean',
            'all_user' => 'nullable|boolean',
            'location' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:500',
        ]);
        // Convert start_time and end_time to Carbon instances
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        // dd($this->users_array);

        // Call the static newEvent function on CalendarEvent
        $res = CalendarEvent::newEvent(
            $this->title,
            $startTime,
            $endTime,
            $this->all_day,
            $this->all_user,
            $this->location,
            $this->note,
            $this->users_array
        );

        if($res){
            $this->reset(['title', 'start_time', 'end_time', 'all_day', 'all_user', 'location', 'note' ,'newEventSection']);
            $this->alert('success' , 'Event Added!');
        }else{
            $this->alert('failed' , 'server error');
        }
    }

    public function mount()
    {
        // Initialize with one user
        $this->users_array[] = [
            'tag' => '',
            'user_id' => '',
            'guest_name' => '',
        ];
    }


    public function addUser()
    {
        $this->users_array[] = [
            'tag' => '',
            'user_id' => '',
            'guest_name' => '',
        ];
    }

    public function removeUser($index)
    {
        if (count($this->users_array) > 1) {
            unset($this->users_array[$index]);
            $this->users_array = array_values($this->users_array); // Reindex array
        }
    }

    public function closeNewEventSec() {
        $this->newEventSection = false;
    }

    public function openNewEventSec() {
        $this->newEventSection = true;
    }
    public function render()
    {
        $events = [];

        foreach (Task::myTasksQuery(upcoming_only: true, assignedToMeOnly: true)->get() as $t) {
            $events[] =  [
                'id'        =>  "task" . $t->id,
                'title'     => "T. " . $t->title,
                'backgroundColor' => 'blue',
                'allDay'    => true,
                'start'     => (new Carbon($t->due))->subMinutes(15)->toIso8601String(),
                'end'       => (new Carbon($t->due))->toIso8601String(),
                'url'       => url('tasks', $t->id)
            ];
        }

        foreach (Followup::userData(upcoming_only: true, mineOnly: true)->with('called')->get() as $t) {
            $events[] =  [
                'id'        =>  "followup" . $t->id,
                'title'     => "F. " . $t->title . ' - ' . $t->called?->name,
                'backgroundColor' => 'dark',
                'textColor' => 'white',
                'start'     => (new Carbon($t->call_time))->subMinutes(15)->toIso8601String(),
                'end'       => (new Carbon($t->call_time))->toIso8601String(),
                'url'       => url($t->called_type . 's', $t->called_id)
            ];
        }

        foreach (ClientPayment::userData(upcoming_only: true, assigned_only: true)->with('sold_policy', 'sold_policy.client')->get() as $t) {
            $events[] =  [
                'id'        =>  "clientpayment" . $t->id,
                'title'     => "CP. " . $t->sold_policy->client->name . ' ' . $t->sold_policy->policy_number,
                'backgroundColor' => '#75d193', //green
                'allDay'    => true,
                'start'     => (new Carbon($t->due))->subMinutes(15)->toIso8601String(),
                'end'       => (new Carbon($t->due))->toIso8601String(),
                'url'       => url('sold-policies', $t->sold_policy->id)
            ];
        }


        foreach (Offer::userData(upcomingOnly: true, assignedToMe: true)->with('client')->get() as $t) {
            $events[] =  [
                'id'        => "offer" . $t->id,
                'title'     => "OF: " . $t->client->name,
                'backgroundColor' => '#73c2fb', //blue
                'allDay'    => true,
                'start'     => (new Carbon($t->call_time))->subMinutes(15)->toIso8601String(),
                'end'       => (new Carbon($t->call_time))->toIso8601String(),
                'url'       => url('offers', $t->id)
            ];
        }

        foreach (CalendarEvent::userData()->with('event_users')->get() as $t) {

            $events[] =  [
                'id'        => "event" . $t->id,
                'title'     => "$t->title with " . $t->event_users_names,
                'backgroundColor' => '#c5c6c7', //blue
                'allDay'    => true,
                'start'     => (new Carbon($t->start_time))->toIso8601String(),
                'end'       => (new Carbon($t->end_time))->toIso8601String(),
            ];
        }

        $USER_TAGS = CalendarEventUser::TAGS;
        $USERS = User::all();

        return view('livewire.calendar', [
            'events' => $events,
            'USER_TAGS' => $USER_TAGS,
            'USERS' => $USERS
        ]);
    }
}
