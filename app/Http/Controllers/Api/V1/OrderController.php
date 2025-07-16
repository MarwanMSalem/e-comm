<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    // GET /api/v1/orders
    public function index(Request $request)
    {
        $orders = $this->service->getAll($request->query());
        return response()->json($orders);
    }

    // GET /api/v1/orders/my
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

    // GET /api/v1/orders/{id}
    public function show($id)
    {
        $order = $this->service->getById($id);
        return response()->json($order);
    }

    // POST /api/v1/orders
    public function store(OrderRequest $request)
    {
        try {
            $order = $this->service->create($request->validated());
            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // PUT /api/v1/orders/{id}
    public function update(OrderRequest $request, Order $order)
    {
        $user = $request->user();
        // Only admin or the user who created the order can update
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        try {
            $order = $this->service->update($order, $request->validated());
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // DELETE /api/v1/orders/{id}
    public function destroy(Request $request, Order $order)
    {
        $user = $request->user();
        // Only admin or the user who created the order can delete
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $this->service->delete($order);
        return response()->json(['message' => 'Order deleted successfully.']);
    }
}