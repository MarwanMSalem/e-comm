<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        // Only admin can assign employee or update status, others can create
        $user = auth()->user();
        if ($this->isMethod('post')) {
            return $user !== null;
        }
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return $user && $user->role === 'admin';
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