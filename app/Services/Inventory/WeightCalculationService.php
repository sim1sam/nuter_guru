<?php

namespace App\Services\Inventory;

use App\Models\Product;
use App\Models\ProductWeightVariant;
use App\Models\WeightVariant;

class WeightCalculationService
{
    public function roundWeight($value): float
    {
        return round((float) $value, 3);
    }

    public function roundMoney($value): float
    {
        return round((float) $value, 2);
    }

    public function calculateBaseQuantity(Product $product, $quantity, ?WeightVariant $weightVariant = null): float
    {
        $quantity = (float) $quantity;

        if ($quantity <= 0) {
            return 0.0;
        }

        if ($this->isKgProduct($product) && $weightVariant) {
            return $this->roundWeight($quantity * (float) $weightVariant->weight_in_kg);
        }

        return $this->roundWeight($quantity);
    }

    public function isKgProduct(?Product $product): bool
    {
        return $product && strtolower((string) $product->unit_type) === 'kg';
    }

    public function isPcsProduct(?Product $product): bool
    {
        return ! $this->isKgProduct($product);
    }

    public function unitLabel(?Product $product): string
    {
        return $this->isKgProduct($product) ? 'KG' : 'PCS';
    }

    public function variantPurchaseCost(Product $product, WeightVariant $weightVariant): float
    {
        return $this->roundMoney((float) $product->cost_price * (float) $weightVariant->weight_in_kg);
    }

    public function variantSellingPrice(Product $product, WeightVariant $weightVariant, ?ProductWeightVariant $pivot = null): float
    {
        if (strtolower((string) $product->selling_price_mode) === 'custom') {
            $custom = $pivot?->selling_price;
            if ($custom !== null && $custom !== '') {
                return $this->roundMoney((float) $custom);
            }
        }

        $sell = $product->offer_price !== null && $product->offer_price !== ''
            ? (float) $product->offer_price
            : (float) $product->price;

        return $this->roundMoney($sell * (float) $weightVariant->weight_in_kg);
    }

    public function theoreticalAvailableUnits(float $stockBase, WeightVariant $weightVariant): int
    {
        $weight = (float) $weightVariant->weight_in_kg;
        if ($weight <= 0) {
            return 0;
        }

        return (int) floor($this->roundWeight($stockBase) / $weight);
    }

    public function hasEnoughStock(Product $product, float $requiredBase): bool
    {
        return $this->roundWeight((float) $product->qty) + 0.0001 >= $this->roundWeight($requiredBase);
    }

    public function availableStockMessage(Product $product): string
    {
        $qty = $this->roundWeight((float) $product->qty);
        $unit = $this->unitLabel($product);

        return "Insufficient stock. Available stock: {$qty} {$unit}.";
    }

    public function costOfGoodsSold(Product $product, float $baseQuantity): float
    {
        return $this->roundMoney((float) $product->cost_price * $baseQuantity);
    }

    public function formatStock($qty, ?Product $product = null): string
    {
        $qty = $this->roundWeight($qty);
        $unit = $this->unitLabel($product);

        if ($this->isPcsProduct($product) && floor($qty) == $qty) {
            return ((int) $qty).' '.$unit;
        }

        return number_format($qty, 3, '.', '').' '.$unit;
    }
}
