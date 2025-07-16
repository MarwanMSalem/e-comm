<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'date',
        'status',
        'employee_id',
        'is_assigned',
    ];

    public function scopeFilter($query, $params)
    {
        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }
        if (!empty($params['user_id'])) {
            $query->where('user_id', $params['user_id']);
        }
        if (!empty($params['employee_id'])) {
            $query->where('employee_id', $params['employee_id']);
        }
        if (!empty($params['is_assigned'])) {
            $query->where('is_assigned', $params['is_assigned']);
        }
        if (!empty($params['date'])) {
            $query->whereDate('date', $params['date']);
        }
        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function employee()
    {
        return $this->belongsTo(User::class);
    }
}
