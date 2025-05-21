@extends('layouts.admin-layout')

@section('content')
    <div class="container mt-5">
        <h4 class="mb-4">Create New Task</h4>

        <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" value="{{ old('title', $task->title) }}" id="title"
                    name="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date"
                    value="{{ old('due_date', $task->due_date) }}" required>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="">Select priority</option>
                    <option {{ $task->priority === 'low' ? 'selected' : '' }} value="low">Low</option>
                    <option {{ $task->priority === 'medium' ? 'selected' : '' }} value="medium">Medium</option>
                    <option {{ $task->priority === 'high' ? 'selected' : '' }} value="high">High</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Task</button>
        </form>
    </div>
@endsection
