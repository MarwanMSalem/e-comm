<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function all($params = [])
    {
        return Order::filter($params)->paginate(10);
    }

    public function find($id)
    {
        return Order::findOrFail($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data)
    {
        $order->update($data);
        return $order;
    }

    public function delete(Order $order)
    {
        return $order->delete();
    }
}