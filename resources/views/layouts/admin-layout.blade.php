<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Custom Styles --}}
    <style>
        body {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #495057;
            color: #fff;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column p-3">
        <h4 class="text-white mb-4">Admin Panel</h4>
        <a href="{{ route('admin.dashboard') }}"
            class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('admin.tasks.index') }}"
            class="{{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">Tasks</a>
        {{-- <a href="{{ route('admin.clients.index') }}"
            class="{{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">Clients</a> --}}

        <hr class="text-secondary">

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-danger w-100">Logout</button>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        <h2 class="mb-4">@yield('title', 'Dashboard')</h2>
        <hr>
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            });
        </script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @yield('script')

</body>

</html>
