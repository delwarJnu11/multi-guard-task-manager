<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::select('id', 'name')->get();
        $query = Task::select(
            'tasks.id',
            'tasks.title',
            'tasks.description',
            'tasks.user_id',
            'tasks.client_id',
            'tasks.due_date',
            'tasks.priority',
            'users.name as user_name',
            'clients.name as client_name'
        )
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->leftJoin('clients', 'tasks.client_id', '=', 'clients.id');

        // Filter by Priority
        if ($request->filled('priority')) {
            $query->where('tasks.priority', $request->priority);
        }

        // Filter by Due Date
        if ($request->filled('due_date')) {
            $query->whereBetween('due_date', [Carbon::now(), $request->due_date]);
        }

        $tasks = $query->orderBy('id', 'asc')->get();

        if ($request->ajax()) {
            return response()->json(['tasks' => $tasks, 'clients' => $clients]);
        }

        return view('task.index', compact('tasks', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('task.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'required|date',
            'priority' => 'required|string|in:high,medium,low',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'user_id' => Auth::guard('web')->id(),
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('task.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'required|date',
            'priority' => 'required|string|in:high,medium,low',
        ]);

        $task->update($request->all());

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully!']);
    }

    // Task Assign 
    public function taskAssign(Request $request)
    {
        $task = Task::find($request->taskId);

        if (!$task) {
            return response()->json([
                'message' => 'Task not Found!'
            ], 404);
        }
        $task->client_id = $request->clientId;
        $task->save();

        return response()->json([
            'message' => 'Task successfully assigned to client!',
        ], 200);
    }
}
