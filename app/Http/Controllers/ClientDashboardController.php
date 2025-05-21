<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {

        $tasks = Task::select('id', 'title', 'description', 'due_date', 'priority', 'user_id', 'is_completed')->where('client_id', Auth::guard('client')->id())->orderBy('due_date')->get();

        return view('client.dashboard', compact('tasks'));
    }

    public function markComplete(Task $task)
    {
        if ($task->client_id !== Auth::guard('client')->id()) {
            abort(403);
        }

        $task->is_completed = true;
        $task->save();

        return redirect()->back()->with('success', 'Task marked as complete.');
    }
}
