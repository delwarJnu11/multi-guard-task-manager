@extends('layouts.client-layout')

@section('title', 'Client Register')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Client Register</h2>

            <form method="POST" action="{{ route('client.register') }}">
                @csrf
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

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
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Register</button>
            </form>

            <p class="mt-3">Already have an account? <a href="{{ route('client.login') }}">Login here</a></p>
        </div>
    </div>
@endsection
