<?php

namespace App\Http\Controllers;

use App\Models\Users\Task;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks.index');
    }

    public function show($taskId)
    {
        Task::findOrFail($taskId);
        return view('tasks.task_show', compact('taskId'));
    }
}
