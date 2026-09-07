<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    protected $fillable = [
        'return_number',
        'order_id',
        'status',
        'return_date',
        'reason',
        'notes',
        'created_by',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(OrderReturnItem::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
