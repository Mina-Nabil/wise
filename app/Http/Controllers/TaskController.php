<?php

namespace App\Http\Controllers;

use App\Models\Tasks\Task;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks.index');
    }
    public function my()
    {
        return view('tasks.index', [
            "filters"   =>  ["my"]
        ]);
    }

    public function claimsIndex(){
        return view('tasks.claims-index');
    }

    public function endorsementIndex(){
        return view('tasks.endorsement-index');
    }

    public function tempTasksIndex(){
        return view('tasks.temp-index');
    }

    public function show($taskId)
    {
        Task::findOrFail($taskId);
        return view('tasks.show', compact('taskId'));
    }
}
