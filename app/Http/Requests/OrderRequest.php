<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        $user = auth()->user();
        $order = $this->route('order');

        if ($this->isMethod('post')) {
            return $user !== null;
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if ($user && $user->role === 'admin') {
                return true;
            }
            if ($user && $order && $user->role === 'user' && $order->user_id === $user->id) {
                return true;
            }
            return false;
        }

        return false;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('You are not authorized to perform this action.');
    }

    public function rules()
    {
        if ($this->isMethod('post')) {
            return [
                'user_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:products,id',
                'date' => 'required|date',
                'quantity' => 'required|integer|min:1',
            ];
        } else { // PUT or PATCH
            return [
                'employee_id' => 'sometimes|nullable|exists:users,id',
                'status' => 'sometimes|nullable|in:pending,shipped,delivered',
                'is_assigned' => 'sometimes|nullable|boolean',
                'quantity' => 'sometimes|integer|min:1',
                'date' => 'sometimes|date',
                'product_id' => 'sometimes|exists:products,id',
            ];
        }
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'Product does not exist.',
            'user_id.required' => 'User is required.',
            'user_id.exists' => 'User does not exist.',
            'date.required' => 'Order date is required.',
            'date.date' => 'Order date must be a valid date.',
            'employee_id.exists' => 'Employee does not exist.',
            'status.in' => 'Status must be pending, shipped, or delivered.',
            'is_assigned.boolean' => 'Is assigned must be true or false.',
            'quantity.required' => 'Order quantity is required.',
            'quantity.integer' => 'Order quantity must be an integer.',
            'quantity.min' => 'Order quantity must be at least 1.',
        ];
    }
}