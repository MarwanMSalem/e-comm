<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'quantity'
    ];

    public function scopeFilter($query, $params)
    {
        if (!empty($params['search'])) {
            $query->where('name', 'like', '%' . $params['search'] . '%');
        }
        if (!empty($params['category'])) {
            $query->where('category', $params['category']);
        }
        if (!empty($params['min_price'])) {
            $query->where('price', '>=', $params['min_price']);
        }
        if (!empty($params['max_price'])) {
            $query->where('price', '<=', $params['max_price']);
        }
        return $query;
    }
}
