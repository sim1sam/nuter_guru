<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'weight_variant_id',
        'unit',
        'pcs_per_box',
        'ordered_qty',
        'received_qty',
        'returned_qty',
        'unit_cost',
        'line_total',
        'base_quantity',
        'variant_name',
        'unit_weight_kg',
        'weight_in_gram',
    ];

    protected $casts = [
        'ordered_qty' => 'float',
        'received_qty' => 'float',
        'returned_qty' => 'float',
        'unit_cost' => 'float',
        'line_total' => 'float',
        'base_quantity' => 'float',
        'unit_weight_kg' => 'float',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function weightVariant()
    {
        return $this->belongsTo(WeightVariant::class);
    }

    public function pendingQty(): float
    {
        return max(0, (float) $this->ordered_qty - (float) $this->received_qty);
    }

    public function unitLabel(): string
    {
        if ($this->variant_name) {
            return $this->variant_name;
        }

        if ($this->product && $this->product->isKg() && (! $this->unit || in_array($this->unit, ['kg', 'pc', 'pcs'], true))) {
            return 'KG';
        }

        return Unit::label($this->unit);
    }

    public function toBaseQty($qty): float
    {
        $qty = (float) $qty;

        if ($this->unit_weight_kg && (float) $this->unit_weight_kg > 0) {
            return round($qty * (float) $this->unit_weight_kg, 3);
        }

        if ($this->product && $this->product->isKg()) {
            return round($qty, 3);
        }

        return (float) Product::convertToPcs($qty, $this->unit, (int) ($this->pcs_per_box ?: 1));
    }

    public function costPerPc(): float
    {
        if ($this->product && $this->product->isKg()) {
            if ($this->unit_weight_kg && (float) $this->unit_weight_kg > 0) {
                return round((float) $this->unit_cost / (float) $this->unit_weight_kg, 4);
            }

            return (float) $this->unit_cost;
        }

        return Product::costPerPc((float) $this->unit_cost, $this->unit, (int) ($this->pcs_per_box ?: 1));
    }
}
