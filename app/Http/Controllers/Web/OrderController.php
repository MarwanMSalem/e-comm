<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Add at the top

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $params = $request->query();

        if ($user->role === 'admin') {
            // Admin sees all orders
            $orders = $this->service->getAll($params);
            // Get all employees for the assign modal
            $employees = \App\Models\User::where('role', 'employee')->get();
        } elseif ($user->role === 'employee') {
            // Employee sees only assigned orders
            $params['employee_id'] = $user->id;
            $orders = $this->service->getAll($params);
            $employees = collect(); // empty
        } else {
            // User sees only their orders
            $params['user_id'] = $user->id;
            $orders = $this->service->getAll($params);
            $employees = collect(); // empty
        }

        return view('order.index', compact('orders', 'employees'));
    }

    public function myOrders(Request $request)
    {
        $user = $request->user();
        $params = $request->query();
        if ($user->role === 'employee') {
            $params['employee_id'] = $user->id;
        } else {
            $params['user_id'] = $user->id;
        }
        $orders = $this->service->getAll($params);
        return response()->json($orders);
    }

    public function show($id)
    {
        $order = $this->service->getById($id);
        return response()->json($order);
    }

    public function store(OrderRequest $request)
    {
        try {
            $order = $this->service->create($request->validated());
            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(OrderRequest $request, Order $order)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        try {
            $order = $this->service->update($order, $request->validated());
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            // Show error on the edit page and keep the input
            return back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Request $request, Order $order)
    {
        $user = auth()->user();
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        $this->service->delete($order);
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function edit(Order $order)
    {
        $user = auth()->user();

        // Only admin or the user who created the order can edit
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // For admin, pass employees for assignment
        $employees = $user->role === 'admin'
            ? \App\Models\User::where('role', 'employee')->get()
            : collect();

        return view('order.edit', compact('order', 'employees'));
    }

    public function storeFromProduct(Request $request, \App\Models\Product $product)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'user'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity,
        ]);

        try {
            $orderData = [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'date' => now()->toDateString(),
                'quantity' => $request->input('quantity'),
                'status' => 'pending',
                'is_assigned' => false,
            ];
            $this->service->create($orderData);
            return redirect()->route('products.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            Log::error('Order placement failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['quantity' => $e->getMessage()])->withInput();
        }
    }
}
