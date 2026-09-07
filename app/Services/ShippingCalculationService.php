<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Shipping;
use App\Models\WeightVariant;
use App\Services\Inventory\WeightCalculationService;
use Illuminate\Support\Collection;

class ShippingCalculationService
{
    public function __construct(
        protected ?WeightCalculationService $weightService = null
    ) {
        $this->weightService = $weightService ?: new WeightCalculationService();
    }

    /**
     * Total cart weight in grams.
     * KG lines use base_quantity (KG → g). PCS lines use products.weight × qty when set.
     */
    public function cartWeightGrams($cartItems): float
    {
        $grams = 0.0;

        foreach ($this->normalizeCartItems($cartItems) as $item) {
            $grams += $this->lineWeightGrams($item);
        }

        return round($grams, 3);
    }

    public function cartWeightKg($cartItems): float
    {
        return round($this->cartWeightGrams($cartItems) / 1000, 3);
    }

    /**
     * Per-KG rate stored on the shipping rule (shipping_fee column).
     */
    public function ratePerKg(Shipping $rule): float
    {
        if (isset($rule->rate_per_kg) && $rule->rate_per_kg !== null && $rule->rate_per_kg !== '') {
            return round((float) $rule->rate_per_kg, 2);
        }

        $raw = $rule->getRawOriginal('shipping_fee') ?? ($rule->getAttributes()['shipping_fee'] ?? $rule->shipping_fee);

        return round((float) $raw, 2);
    }

    /**
     * Billable KG for shipping:
     * - Any weight in (0, 1] bills as 1 KG (full first slab).
     * - Above 1 KG bills exact weight (1.5 KG = 1 + 0.5 → rate + half rate).
     * Example Inside ৳70: 0.5 KG → ৳70; 1.5 KG → ৳70+৳35 = ৳105; 2 KG → ৳140.
     */
    public function billableKg(float $weightKg): float
    {
        if ($weightKg <= 0) {
            return 0.0;
        }

        return round(max(1.0, $weightKg), 3);
    }

    /**
     * Fee = rate × billable KG.
     * When cart weight is 0 (PCS only), charge flat rate once (legacy).
     */
    public function feeFromRate(float $ratePerKg, float $weightKg): float
    {
        if ($weightKg > 0) {
            return round($ratePerKg * $this->billableKg($weightKg), 2);
        }

        return round($ratePerKg, 2);
    }

    /**
     * @return float|null null if rule missing
     */
    public function resolveFee(?Shipping $rule, $cartItems): ?float
    {
        if (! $rule) {
            return null;
        }

        return $this->feeFromRate(
            $this->ratePerKg($rule),
            $this->cartWeightKg($cartItems)
        );
    }

    public function availableMethods($cartItems, $cityId = null): Collection
    {
        $query = Shipping::query()->orderBy('id');
        if ($cityId !== null && $cityId !== '') {
            $query->where(function ($q) use ($cityId) {
                $q->where('city_id', 0)->orWhere('city_id', (int) $cityId);
            });
        }

        return $query->get();
    }

    /**
     * Attach resolved fee for frontend (shipping_fee / cost = total for this cart).
     * rate_per_kg keeps the admin-configured ৳/KG value.
     */
    public function decorateMethods(Collection $methods, $cartItems): Collection
    {
        $weightKg = $this->cartWeightKg($cartItems);

        return $methods->map(function (Shipping $shipping) use ($cartItems, $weightKg) {
            $rate = $this->ratePerKg($shipping);
            $fee = $this->resolveFee($shipping, $cartItems) ?? 0;

            $shipping->rate_per_kg = $rate;
            $shipping->cart_weight_kg = $weightKg;
            $shipping->billable_kg = $this->billableKg($weightKg);
            $shipping->resolved_fee = $fee;
            $shipping->cost = $fee;
            $shipping->setAttribute('shipping_fee', $fee);

            return $shipping;
        })->values();
    }

    protected function lineWeightGrams(array $item): float
    {
        $product = $item['product'] ?? null;
        if (! $product instanceof Product) {
            return 0.0;
        }

        $qty = (float) ($item['qty'] ?? 0);
        if ($qty <= 0) {
            return 0.0;
        }

        if (isset($item['base_quantity']) && $item['base_quantity'] !== null && $item['base_quantity'] !== '') {
            $baseKg = (float) $item['base_quantity'];
            if ($this->weightService->isKgProduct($product) || ($baseKg > 0 && ! empty($item['weight_variant_id']))) {
                return round($baseKg * 1000, 3);
            }
        }

        if ($this->weightService->isKgProduct($product)) {
            $variant = $item['weight_variant'] ?? null;
            if (! $variant && ! empty($item['weight_variant_id'])) {
                $variant = WeightVariant::find($item['weight_variant_id']);
            }
            if ($variant) {
                $baseKg = $this->weightService->calculateBaseQuantity($product, $qty, $variant);

                return round($baseKg * 1000, 3);
            }

            return round($qty * 1000, 3);
        }

        $productWeight = (float) ($product->weight ?? 0);
        if ($productWeight > 0) {
            return round($productWeight * $qty, 3);
        }

        return 0.0;
    }

    protected function normalizeCartItems($cartItems): array
    {
        $items = [];
        foreach ($cartItems ?? [] as $raw) {
            if (is_array($raw)) {
                $product = $raw['product'] ?? null;
                if (! $product && ! empty($raw['product_id'])) {
                    $product = Product::find($raw['product_id']);
                }
                $items[] = [
                    'product' => $product,
                    'qty' => $raw['qty'] ?? $raw['quantity'] ?? 0,
                    'base_quantity' => $raw['base_quantity'] ?? null,
                    'weight_variant_id' => $raw['weight_variant_id'] ?? null,
                    'weight_variant' => $raw['weight_variant'] ?? null,
                    'unit_weight_kg' => $raw['unit_weight_kg'] ?? null,
                    'variants' => $raw['variants'] ?? [],
                ];
                continue;
            }

            if (is_object($raw)) {
                $product = $raw->product ?? null;
                if (! $product && ! empty($raw->product_id)) {
                    $product = Product::find($raw->product_id);
                }
                $items[] = [
                    'product' => $product,
                    'qty' => $raw->qty ?? $raw->quantity ?? 0,
                    'base_quantity' => $raw->base_quantity ?? null,
                    'weight_variant_id' => $raw->weight_variant_id ?? null,
                    'weight_variant' => $raw->weightVariant ?? ($raw->weight_variant ?? null),
                    'unit_weight_kg' => $raw->unit_weight_kg ?? null,
                    'variants' => $raw->variants ?? [],
                ];
            }
        }

        return $items;
    }
}
