<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'total_amount',
        'product_qty',
        'payment_method',
        'payment_status',
        'payment_approval_date',
        'transection_id',
        'payment_screenshot',
        'steadfast_consignment_id',
        'steadfast_tracking_code',
        'steadfast_status',
        'steadfast_response',
        'shipping_method',
        'shipping_cost',
        'coupon_coast',
        'order_status',
        'order_approval_date',
        'order_delivered_date',
        'order_completed_date',
        'order_declined_date',
        'stock_deducted_at',
        'stock_restored_at',
        'cash_on_delivery',
        'additional_info'
    ];

    protected $casts = [
        'stock_deducted_at' => 'datetime',
        'stock_restored_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orderProducts(){
        return $this->hasMany(OrderProduct::class);
    }

    public function orderAddress(){
        return $this->hasOne(OrderAddress::class);
    }
}
