@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="mb-4 text-center">Login</h2>
            <form method="POST" action="{{ url('web/login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email') }}" 
                        required
                    >
                    @error('email') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        class="form-control @error('password') is-invalid @enderror" 
                        required
                    >
                    @error('password') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-3 text-center">
                Don't have an account? 
                <a href="{{ url('web/register') }}">Register</a>
            </p>
        </div>
    </div>
</div>
@endsection