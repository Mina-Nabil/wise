<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Tasks\Task;
use App\Models\Cars\Car;
use App\Models\Customers\Customer;
use App\Models\Customers\Status;
use App\Models\Users\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\WithPagination;

class Home extends Component
{
    use WithPagination;

    public function render()
    {
        $countTasks = Task::assignedTo(auth()->user()->id)->count();

        $compTasks = Task::where('status', 'completed')
            ->whereYear('updated_at', Carbon::now()->year)
            ->whereMonth('updated_at', Carbon::now()->month)
            ->count();

        $recentTasks =  Task::assignedTo(auth()->user()->id)->get()->take(5);

        $countCars = Car::all()->count();
        $homeAssignedOffers = Auth::user()->homeAssignedOffers(5);
        $homeCreatedOffers = Auth::user()->homeCreatedOffers(5);
        $homeFollowups = Auth::user()->homeFollowups(5);
        $homeCustomers = Auth::user()->homeCustomers(5);
        $homeCorporates = Auth::user()->homeCorporates(5);

        $customerStatus = Status::STATUSES;
        // dd($homeFollowups);
        return view('livewire.home', [
            'countTasks' => $countTasks,
            'compTasks' => $compTasks,
            'countCars' => $countCars,
            'recentTasks' => $recentTasks,
            'homeAssignedOffers' => $homeAssignedOffers,
            'homeCreatedOffers' => $homeCreatedOffers,
            'homeFollowups' => $homeFollowups,
            'homeCustomers' => $homeCustomers,
            'homeCorporates' => $homeCorporates,
            'customerStatus' => $customerStatus
        ]);
    }
}
