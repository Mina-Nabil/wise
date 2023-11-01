<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Tasks\Task;
use App\Models\Cars\Car;
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

        return view('livewire.home', [
            'countTasks' => $countTasks,
            'compTasks' => $compTasks,
            'countCars' => $countCars,
            'recentTasks' => $recentTasks,
        ]);
    }
}
