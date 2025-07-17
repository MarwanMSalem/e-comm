<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        $user = auth()->user();
        $order = $this->route('order'); // This will be the Order model instance

        if ($this->isMethod('post')) {
            return $user !== null;
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // Admin can update any order
            if ($user && $user->role === 'admin') {
                return true;
            }
            // User can update their own order (only quantity)
            if ($user && $order && $user->role === 'user' && $order->user_id === $user->id) {
                // Optionally, you can check that only 'quantity' is being updated here
                return true;
            }
            // Employee cannot update
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
        $rules = [
            'product_id' => 'required|exists:products,id',
            'date' => 'required|date',
            'quantity' => 'required|integer|min:1',
        ];

        if ($this->isMethod('post')) {
            $rules['user_id'] = 'required|exists:users,id';
        }
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['employee_id'] = 'nullable|exists:users,id';
            $rules['status'] = 'nullable|in:pending,shipped,delivered';
            $rules['is_assigned'] = 'nullable|boolean';
        }
        return $rules;
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