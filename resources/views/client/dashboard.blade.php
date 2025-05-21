@extends('layouts.client-layout')

@section('content')
    <div class="container">
        <h2 class="mb-4">My Tasks</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($tasks->count())
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Due Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</td>
                            <td>{{ ucfirst($task->priority) }}</td>
                            <td>
                                @if ($task->is_completed)
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if (!$task->is_completed)
                                    <form action="{{ route('client.tasks.complete', $task->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">Mark as Complete</button>
                                    </form>
                                @else
                                    <span class="text-muted">Done</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No tasks assigned yet.</p>
        @endif
    </div>
@endsection
