<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\WeightVariant;
use App\Services\Inventory\WeightCalculationService;
use PHPUnit\Framework\TestCase;

class WeightCalculationServiceTest extends TestCase
{
    public function test_calculates_base_quantity_for_weight_variant(): void
    {
        $service = new WeightCalculationService();
        $product = new Product(['unit_type' => 'kg']);
        $variant = new WeightVariant([
            'name' => '500g',
            'weight_in_kg' => 0.5,
            'weight_in_gram' => 500,
        ]);

        $this->assertSame(1.5, $service->calculateBaseQuantity($product, 3, $variant));
    }

    public function test_pcs_base_quantity_is_quantity(): void
    {
        $service = new WeightCalculationService();
        $product = new Product(['unit_type' => 'pcs']);

        $this->assertSame(3.0, $service->calculateBaseQuantity($product, 3, null));
    }

    public function test_variant_purchase_and_selling_price(): void
    {
        $service = new WeightCalculationService();
        $product = new Product([
            'unit_type' => 'kg',
            'cost_price' => 80,
            'price' => 100,
            'selling_price_mode' => 'automatic',
        ]);
        $variant = new WeightVariant(['weight_in_kg' => 0.5, 'weight_in_gram' => 500]);

        $this->assertSame(40.0, $service->variantPurchaseCost($product, $variant));
        $this->assertSame(50.0, $service->variantSellingPrice($product, $variant));
    }

    public function test_theoretical_available_units(): void
    {
        $service = new WeightCalculationService();
        $variant = new WeightVariant(['weight_in_kg' => 0.5]);

        $this->assertSame(7, $service->theoreticalAvailableUnits(3.750, $variant));
    }

    public function test_stock_validation_message_and_enough_stock(): void
    {
        $service = new WeightCalculationService();
        $product = new Product(['unit_type' => 'kg', 'qty' => 1]);

        $this->assertFalse($service->hasEnoughStock($product, 1.5));
        $this->assertStringContainsString('1', $service->availableStockMessage($product));
        $this->assertStringContainsString('KG', $service->availableStockMessage($product));
    }
}
