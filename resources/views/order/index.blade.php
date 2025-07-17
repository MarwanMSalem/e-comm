@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Orders</h2>
    <form method="GET" action="{{ route('orders.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="status" class="form-control" placeholder="Status..." value="{{ request('status') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" placeholder="Date..." value="{{ request('date') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="is_assigned" class="form-control" placeholder="Is Assigned (0/1)..." value="{{ request('is_assigned') }}">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100" type="submit">Filter</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>User</th>
                <th>Employee</th>
                <th>Date</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Is Assigned</th>
                <th style="width: 220px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->product->name ?? '-' }}</td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td>{{ $order->employee ? $order->employee->name : '-' }}</td>
                <td>{{ $order->date }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ $order->quantity }}</td>
                <td>
                    @if($order->is_assigned)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-secondary">No</span>
                    @endif
                </td>
                <td>
                    <!-- Admin Actions -->
                    @if(auth()->user()->role === 'admin')
                        <!-- Assign Employee Button -->
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#assignModal{{ $order->id }}">Assign</button>
                        <!-- Edit Button -->
                        {{-- <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">Edit</a> --}}
                        <!-- Delete Button -->
                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this order?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    @elseif(auth()->user()->role === 'user' && $order->user_id === auth()->id())
                        <!-- User can edit/delete their own orders -->
                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this order?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    @elseif(auth()->user()->role === 'employee' && $order->employee_id === auth()->id())
                        <span class="text-muted">Assigned</span>
                    @endif
                </td>
            </tr>

            <!-- Assign Modal (admin only) -->
            @if(auth()->user()->role === 'admin')
            <div class="modal fade" id="assignModal{{ $order->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('orders.update', $order) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="assignModalLabel{{ $order->id }}">Assign Employee & Update Status</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Employee</label>
                                    <select name="employee_id" class="form-select" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ $order->employee_id == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </div>
                                <input type="hidden" name="is_assigned" value="1">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Assign & Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @empty
            <tr>
                <td colspan="9" class="text-center">No orders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div>
        {{ $orders->withQueryString()->links() }}
    </div>
</div>

@if ($errors->any() && session('assign_modal_order_id'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('assignModal{{ session('assign_modal_order_id') }}'));
        modal.show();
    });
</script>
@endif
@endsection
