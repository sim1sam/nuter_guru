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
        'shipping_method',
        'shipping_cost',
        'coupon_coast',
        'order_status',
        'order_approval_date',
        'order_delivered_date',
        'order_completed_date',
        'order_declined_date',
        'cash_on_delivery',
        'additional_info'
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
