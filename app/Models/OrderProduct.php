<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'seller_id',
        'product_name',
        'unit_price',
        'qty',
        'weight_variant_id',
        'variant_name_snapshot',
        'unit_weight_kg',
        'weight_in_gram',
        'base_quantity',
        'unit_cost',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'qty' => 'float',
        'unit_weight_kg' => 'float',
        'base_quantity' => 'float',
        'unit_cost' => 'float',
    ];

    public function seller()
    {
        return $this->belongsTo(Vendor::class, 'seller_id');
    }

    public function orderProductVariants()
    {
        return $this->hasMany(OrderProductVariant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
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
