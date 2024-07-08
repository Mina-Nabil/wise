<?php

namespace App\Http\Livewire;

use App\Models\Customers\Followup;
use App\Models\Event;
use App\Models\Offers\Offer;
use App\Models\Payments\ClientPayment;
use App\Models\Tasks\Task;
use App\Models\Users\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Calendar extends Component
{

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

        foreach (Followup::userData(upcoming_only: true)->with('called')->get() as $t) {
            $events[] =  [
                'id'        =>  "followup" . $t->id,
                'title'     => "F. " . $t->title . ' - ' . $t->called?->name,
                'backgroundColor' => 'dark',
                'textColor' => 'white',
                'start'     => (new Carbon($t->call_time))->subMinutes(15)->toIso8601String(),
                'end'       => (new Carbon($t->call_time))->toIso8601String(),
                'url'       => url($t->called_type . 's' , $t->called_id)
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

        return view('livewire.calendar', [
            'events' => $events
        ]);
    }
}
