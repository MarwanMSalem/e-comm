@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body text-center">
            <h2>Welcome{{ auth()->check() ? ', ' . auth()->user()->name : '' }}!</h2>
            <p class="lead">You are now logged in.</p>
            <a href="{{ url('products') }}" class="btn btn-primary mt-3">Browse Products</a>
        </div>
    </div>
</div>
@endsection
