<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class OrderService
{
    protected $repo;

    public function __construct(OrderRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll($params)
    {
        return $this->repo->all($params);
    }

    public function getById($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);
            if ($product->quantity < $data['quantity']) {
                throw new \Exception('Not enough product in stock.');
            }
            $product->decrement('quantity', $data['quantity']);
            return $this->repo->create($data);
        });
    }

    public function update(Order $order, array $data)
    {
        // Set is_assigned based on employee_id
        if (array_key_exists('employee_id', $data)) {
            $data['is_assigned'] = !empty($data['employee_id']) ? true : false;
        }

        return DB::transaction(function () use ($order, $data) {
            // If quantity is being updated, adjust product stock
            if (isset($data['quantity'])) {
                $product = Product::findOrFail($order->product_id);
                $diff = $data['quantity'] - $order->quantity;
                if ($diff > 0 && $product->quantity < $diff) {
                    throw new \Exception('Not enough product in stock for update.');
                }
                $product->decrement('quantity', $diff);
            }
            $order = $this->repo->update($order, $data);
            return $order;
        });
    }

    public function delete(Order $order)
    {
        return DB::transaction(function () use ($order) {
            // Restore product quantity
            $product = Product::findOrFail($order->product_id);
            $product->increment('quantity', $order->quantity);
            return $this->repo->delete($order);
        });
    }
}