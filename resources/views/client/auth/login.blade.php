@extends('layouts.client-layout')

@section('title', 'Client Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Client Login</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('client.login') }}">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <p class="mt-3">Don't have an account? <a href="{{ route('client.register') }}">Register here</a></p>
        </div>
    </div>
@endsection
