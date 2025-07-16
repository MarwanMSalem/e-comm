<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user() && auth()->user()->role === 'admin';
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Only admin users are allowed to perform this action.');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name is required.',
            'price.required' => 'Product price is required.',
            'price.numeric' => 'Product price must be a number.',
            'price.min' => 'Product price must be at least 0.',
            'category.required' => 'Product category is required.',
            'quantity.required' => 'Product quantity is required.',
            'quantity.integer' => 'Product quantity must be an integer.',
            'quantity.min' => 'Product quantity must be at least 0.',
        ];
    }
}
