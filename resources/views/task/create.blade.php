@extends('layouts.admin-layout')

@section('content')
    <div class="container mt-5">
        <h4 class="mb-4">Create New Task</h4>

        <form action="{{ route('admin.tasks.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" value="{{ old('title') }}" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="">Select priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit Task</button>
        </form>
    </div>
@endsection
