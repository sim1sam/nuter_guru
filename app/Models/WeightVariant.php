<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightVariant extends Model
{
    protected $fillable = [
        'name',
        'code',
        'weight_in_kg',
        'weight_in_gram',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'weight_in_kg' => 'float',
        'weight_in_gram' => 'integer',
        'status' => 'integer',
        'sort_order' => 'integer',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_weight_variants')
            ->withPivot(['selling_price', 'barcode'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('weight_in_kg');
    }
}
