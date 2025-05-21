<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Client Portal - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap (optional) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Additional CSS --}}
    <style>
        body {
            padding-top: 60px;
        }
    </style>
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('client.dashboard') }}">Client Portal</a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto flex items-center">
                    @auth('client')
                        <li class="nav-item">
                            <span class="nav-link text-white">{{ Auth::guard('client')->user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('client.logout') }}">
                                @csrf
                                <button class="btn btn-sm btn-danger">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('client.login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('client.register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <div class="container mt-4">
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
