<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWeightVariant extends Model
{
    protected $fillable = [
        'product_id',
        'weight_variant_id',
        'selling_price',
        'barcode',
    ];

    protected $casts = [
        'selling_price' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function weightVariant()
    {
        return $this->belongsTo(WeightVariant::class);
    }
}
