<?php

namespace App\Http\Livewire;

use App\Models\Customers\Followup;
use App\Models\Event;
use App\Models\Offers\Offer;
use App\Models\Payments\ClientPayment;
use App\Models\Tasks\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Calendar extends Component
{

    public function render()
    {
        $events = [];

        foreach (Task::myTasksQuery(upcoming_only: true)->get() as $t) {
            $events[] =  [
                'id' => $t->id,
                'title' => "Task: " . $t->title,
                'backgroundColor' => 'blue',
                'allDay' => true,
                'start' => (new Carbon($t->due))->subMinutes(15)->toIso8601String(),
                'end' => (new Carbon($t->due))->toIso8601String(),
            ];
        }

        foreach (Followup::userData(upcoming_only: true)->get() as $t) {
            $events[] =  [
                'id' => $t->id,
                'title' => "Follow up: " . $t->title,
                'backgroundColor' => 'dark',
                'textColor' => 'white',
                'start' => (new Carbon($t->call_time))->subMinutes(15)->toIso8601String(),
                'end' => (new Carbon($t->call_time))->toIso8601String(),
            ];
        }

        foreach (ClientPayment::userData(upcoming_only: true)->with('sold_policy', 'sold_policy.client')->get() as $t) {
            $events[] =  [
                'id' => $t->id,
                'title' => "Client Payment: " . $t->sold_policy->client->name . ' ' . $t->sold_policy->policy_number,
                'backgroundColor' => '#70c247',
                'allDay' => true,
                'start' => (new Carbon($t->due))->subMinutes(15)->toIso8601String(),
                'end' => (new Carbon($t->due))->toIso8601String(),
            ];
        }


        foreach (Offer::userData(upcomingOnly: true, assignedToMe: true)->with('client')->get() as $t) {
            $events[] =  [
                'id' => $t->id,
                'title' => "Offer: " . $t->client->name,
                'backgroundColor' => '#73c2fb', //blue
                'allDay' => true,
                'start' => (new Carbon($t->call_time))->subMinutes(15)->toIso8601String(),
                'end' => (new Carbon($t->call_time))->toIso8601String(),
            ];
        }

        return view('livewire.calendar', [
            'events' => $events
        ]);
    }
}
