<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Tasks\Task;
use App\Models\Cars\Car;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Home extends Component
{
    public function render()
    {
        $countTasks = Task::assignedTo(auth()->user()->id)->count();

        $compTasks = Task::where('status', 'completed')
            ->whereYear('updated_at', Carbon::now()->year)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->count();

        $recentTasks =  Task::assignedTo(auth()->user()->id)->get()->take(5);

        $countCars = Car::all()->count();
        $homeAssignedOffers = Auth::user()->homeAssignedOffers(false);
        $homeCreatedOffers = Auth::user()->homeCreatedOffers(false);
        $homeFollowups = Auth::user()->homeFollowups(false);
        $homeCustomers = Auth::user()->homeCustomers(false);
        $homeCorporates = Auth::user()->homeCorporates(false);

        
        return view('livewire.home', [
            'countTasks' => $countTasks,
            'compTasks' => $compTasks,
            'countCars' => $countCars,
            'recentTasks' => $recentTasks,
            'homeAssignedOffers' => $homeAssignedOffers,
            'homeCreatedOffers' => $homeCreatedOffers,
            'homeFollowups' => $homeFollowups,
            'homeCustomers' => $homeCustomers,
            'homeCorporates' => $homeCorporates
        ]);
    }
}
