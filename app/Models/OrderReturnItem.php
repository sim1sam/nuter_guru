<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturnItem extends Model
{
    protected $fillable = [
        'order_return_id',
        'order_product_id',
        'product_id',
        'weight_variant_id',
        'variant_name_snapshot',
        'unit_weight_kg',
        'qty',
        'base_quantity',
        'unit_price',
    ];

    protected $casts = [
        'unit_weight_kg' => 'float',
        'qty' => 'float',
        'base_quantity' => 'float',
        'unit_price' => 'float',
    ];

    public function orderReturn()
    {
        return $this->belongsTo(OrderReturn::class);
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function weightVariant()
    {
        return $this->belongsTo(WeightVariant::class);
    }
}
