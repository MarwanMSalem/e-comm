@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="mb-4">Edit Order #{{ $order->id }}</h2>
            <form method="POST" action="{{ route('orders.update', $order) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Product</label>
                    <input type="text" class="form-control" value="{{ $order->product->name ?? '-' }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="text" class="form-control" value="{{ $order->date }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    @if(auth()->user()->role === 'admin')
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ ucfirst($order->status) }}" readonly>
                    @endif
                </div>
                @if(auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label class="form-label">Assigned Employee</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $order->employee_id == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="mb-3">
                    <label class="form-label">Assigned Employee</label>
                    <input type="text" class="form-control" value="{{ $order->employee ? $order->employee->name : '-' }}" readonly>
                </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $order->quantity) }}" min="1" required>
                    @error('quantity') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
