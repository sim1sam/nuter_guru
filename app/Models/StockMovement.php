<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'type',
        'reason',
        'qty',
        'qty_before',
        'qty_after',
        'unit',
        'base_quantity',
        'weight_variant_id',
        'variant_name',
        'unit_weight_kg',
        'reference_no',
        'reference_type',
        'reference_id',
        'supplier_id',
        'unit_cost',
        'note',
        'admin_id',
        'related_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function weightVariant()
    {
        return $this->belongsTo(WeightVariant::class);
    }
}
