@extends('layouts.admin-layout')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-3">Task Details</h4>
            <div class="mb-3 d-flex gap-3 align-items-end">
                <div>
                    <label for="filter-priority">Priority:</label>
                    <select id="filter-priority" class="form-select">
                        <option value="">All</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <div>
                    <label for="filter-due-date">Due Date:</label>
                    <input type="date" id="filter-due-date" class="form-control" />
                </div>
            </div>

            <a class="btn btn-primary px-4 py-2 text-decoration-none" href="{{ route('admin.tasks.create') }}"> <i
                    class="fa fa-plus"></i> Create Task</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Created By</th>
                    <th>Assign Task</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sl = 0;
                @endphp
                @forelse ($tasks as $task)
                    @php
                        $sl++;
                    @endphp
                    <tr>
                        <td>{{ $sl }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($task->title, 10, '...') }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($task->description, 30, '...') }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>{{ $task->priority }}</td>
                        <td>{{ $task->user_name }}</td>
                        <td>
                            @if ($task->client_id)
                                {{ $task->client_name }}
                            @else
                                <select class="assign-task" data-id="{{ $task->id }}" name="client_id" id="client_id">
                                    <option value="">Assign Task</option>
                                    @forelse ($clients as $client)
                                        <option {{ $task->client_id === $client->id ? 'selected' : '' }}
                                            value="{{ $client->id }}">{{ $client->name }}</option>
                                    @empty
                                        <option value="">No Client Found!</option>
                                    @endforelse
                                </select>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-success px-3 py-1">Edit</a>
                            <a href="javascript:void(0)" class="btn btn-danger px-3 py-1 delete-task-btn"
                                data-id="{{ $task->id }}">
                                Delete
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No Task Found!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            });
            // Update task and assign Task
            $('tbody').on('change', '.assign-task', function() {
                const clientId = $(this).val();
                const taskId = $(this).data('id');
                console.log(clientId)

                $.ajax({
                    url: "{{ route('admin.task-assign') }}",
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        taskId: taskId,
                        clientId: clientId
                    }),
                    success: function(response) {
                        Swal.fire({
                            title: 'success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Close'
                        });
                        window.location.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('Something went wrong!');
                    }
                });
            });

            // Delete task
            $('.delete-task-btn').on('click', function() {
                const taskId = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/admin/tasks/${taskId}`,
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            function fetchFilteredTasks() {
                const priority = $('#filter-priority').val();
                const dueDate = $('#filter-due-date').val();

                $.ajax({
                    url: "{{ route('admin.tasks.index') }}",
                    method: 'GET',
                    data: {
                        priority: priority,
                        due_date: dueDate
                    },
                    success: function(response) {
                        let rows = '';

                        if (response?.tasks?.length === 0) {
                            rows = `<tr><td colspan="8" class="text-center">No tasks found.</td></tr>`;
                        } else {
                            response?.tasks.forEach((task, index) => {
                                let clientColumn = '';

                                // If client already assigned, show name
                                if (task.client_name) {
                                    clientColumn = task.client_name;
                                } else {
                                    clientColumn = `<select class="assign-task" data-id="${task.id}" name="client_id">
                                <option value="">Assign Task</option>`;
                                    response?.clients.forEach(client => {
                                        const selected = task.client_id === client.id ?
                                            'selected' : '';
                                        clientColumn +=
                                            `<option value="${client.id}" ${selected}>${client.name}</option>`;
                                    });
                                    clientColumn += `</select>`;
                                }

                                rows += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${task.title.substring(0, 10)}...</td>
                                        <td>${task.description.substring(0, 30)}...</td>
                                        <td>${task.due_date}</td>
                                        <td>${task.priority}</td>
                                        <td>${task.user_name}</td>
                                        <td>${clientColumn}</td>
                                        <td>
                                            <a href="/admin/tasks/${task.id}/edit" class="btn btn-success px-3 py-1">Edit</a>
                                            <a href="javascript:void(0)" class="btn btn-danger px-3 py-1 delete-task-btn" data-id="${task.id}">Delete</a>
                                        </td>
                                    </tr>`;
                            });
                        }

                        $('table tbody').html(rows);
                    },
                    error: function() {
                        alert('Failed to filter tasks.');
                    }
                });
            }

            // Trigger filter on change
            $('#filter-priority, #filter-due-date').on('change', fetchFilteredTasks);

        });
    </script>
@endsection
