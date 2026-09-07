<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'qty',
        'coupon_name',
        'coupon_price',
        'offer_type',
        'weight_variant_id',
        'variant_name_snapshot',
        'unit_weight_kg',
        'base_quantity',
        'unit_price',
    ];

    protected $casts = [
        'qty' => 'float',
        'unit_weight_kg' => 'float',
        'base_quantity' => 'float',
        'unit_price' => 'float',
    ];

    public function variants()
    {
        return $this->hasMany(ShoppingCartVariant::class, 'shopping_cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->select([
            'id', 'name', 'name_bn', 'short_name', 'short_name_bn', 'price', 'offer_price',
            'thumb_image', 'slug', 'weight', 'qty', 'unit_type', 'selling_price_mode', 'cost_price',
        ]);
    }

    public function card_product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function weightVariant()
    {
        return $this->belongsTo(WeightVariant::class);
    }
}
