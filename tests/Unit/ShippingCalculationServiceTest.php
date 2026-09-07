<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Shipping;
use App\Models\WeightVariant;
use App\Services\ShippingCalculationService;
use PHPUnit\Framework\TestCase;

class ShippingCalculationServiceTest extends TestCase
{
    public function test_first_kg_full_then_proportional(): void
    {
        $service = new ShippingCalculationService();

        // 0–1 KG: always full first slab
        $this->assertSame(1.0, $service->billableKg(0.25));
        $this->assertSame(1.0, $service->billableKg(0.5));
        $this->assertSame(1.0, $service->billableKg(1.0));
        $this->assertSame(70.0, $service->feeFromRate(70, 0.5));
        $this->assertSame(100.0, $service->feeFromRate(100, 0.5));

        // 1.5 KG = 70 + 35
        $this->assertSame(1.5, $service->billableKg(1.5));
        $this->assertSame(105.0, $service->feeFromRate(70, 1.5));
        $this->assertSame(150.0, $service->feeFromRate(100, 1.5));

        // Exact multiplies above 1 KG
        $this->assertSame(140.0, $service->feeFromRate(70, 2));
        $this->assertSame(200.0, $service->feeFromRate(100, 2));
        $this->assertSame(175.0, $service->feeFromRate(70, 2.5));

        // Flat when no weight (PCS cart)
        $this->assertSame(70.0, $service->feeFromRate(70, 0));
    }

    public function test_two_250g_packs_charge_70_inside(): void
    {
        $service = new ShippingCalculationService();
        $product = new Product(['unit_type' => 'kg', 'weight' => 0]);
        $variant = new WeightVariant([
            'name' => '250g',
            'weight_in_kg' => 0.25,
            'weight_in_gram' => 250,
        ]);

        $cart = [
            [
                'product' => $product,
                'qty' => 2,
                'base_quantity' => 0.5,
                'weight_variant_id' => 1,
                'weight_variant' => $variant,
            ],
        ];

        $this->assertSame(0.5, $service->cartWeightKg($cart));
        $inside = new Shipping(['shipping_fee' => 70, 'type' => 'base_on_weight']);
        $this->assertSame(70.0, $service->resolveFee($inside, $cart));
    }

    public function test_1_5_kg_is_70_plus_35(): void
    {
        $service = new ShippingCalculationService();
        $product = new Product(['unit_type' => 'kg']);
        $cart = [[
            'product' => $product,
            'qty' => 3,
            'base_quantity' => 1.5, // e.g. 250g×2 + 1kg, or 500g×3
            'weight_variant_id' => 1,
        ]];

        $inside = new Shipping(['shipping_fee' => 70, 'type' => 'base_on_weight']);
        $outside = new Shipping(['shipping_fee' => 100, 'type' => 'base_on_weight']);

        $this->assertSame(1.5, $service->cartWeightKg($cart));
        $this->assertSame(105.0, $service->resolveFee($inside, $cart));
        $this->assertSame(150.0, $service->resolveFee($outside, $cart));
    }
}
