<?php

function custom_sanitize($content)
{
    $replace = array('<p>', '</p>');
    $response = str_replace($replace, '', $content);
    return $response;
}

/**
 * Encode order ID for secure URL
 */
function encodeOrderId($orderId)
{
    try {
        return base64_encode(encrypt($orderId));
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Decode order ID from secure URL
 */
function decodeOrderId($encodedOrderId)
{
    try {
        return decrypt(base64_decode($encodedOrderId));
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Check if product has discount (offer price)
 */
function checkDiscount($product)
{
    return $product->offer_price && $product->offer_price < $product->price;
}

/**
 * Frontend theme CSS variable values from admin settings.
 */
function theme_variables($setting = null): array
{
    return \App\Helpers\ThemeHelper::variables($setting);
}

/**
 * Store currency symbol from admin settings (default BDT).
 */
function currency_icon($setting = null): string
{
    if ($setting && ! empty($setting->currency_icon)) {
        return (string) $setting->currency_icon;
    }

    $setting = $setting ?: \App\Models\Setting::first();

    return (string) ($setting->currency_icon ?? '৳');
}

/**
 * Format amount with store currency symbol.
 */
function format_currency($amount, int $decimals = 2, $setting = null): string
{
    return currency_icon($setting) . number_format((float) $amount, $decimals);
}

/**
 * Product display name for the active locale (Bangla / English).
 */
function product_name($product): string
{
    if (! $product) {
        return '';
    }

    if (is_array($product)) {
        $locale = app()->getLocale();
        if ($locale === 'bn' && ! empty($product['name_bn'])) {
            return (string) $product['name_bn'];
        }

        return (string) ($product['name'] ?? '');
    }

    return (string) ($product->localized_name ?? $product->name ?? '');
}

/**
 * Category / subcategory display name for the active locale.
 */
function category_name($category): string
{
    if (! $category) {
        return '';
    }

    if (is_array($category)) {
        $locale = app()->getLocale();
        if ($locale === 'bn' && ! empty($category['name_bn'])) {
            return (string) $category['name_bn'];
        }

        return (string) ($category['name'] ?? '');
    }

    return (string) ($category->localized_name ?? $category->name ?? '');
}

/**
 * Order status label: Pending, Processing, Shipment, Delivered, Return, Cancel.
 */
function order_status_label($status): string
{
    return match ((int) $status) {
        1 => __('admin.Processing'),
        5 => __('admin.Shipment'),
        2 => __('admin.Delivered'),
        3 => __('admin.Return'),
        4 => __('admin.Cancel'),
        default => __('admin.Pending'),
    };
}

/**
 * Slider title/description for the active locale.
 */
function slider_text($slider, string $field = 'title_one'): string
{
    if (! $slider) {
        return '';
    }

    $bnField = $field . '_bn';

    if (is_array($slider)) {
        if (app()->getLocale() === 'bn' && ! empty($slider[$bnField])) {
            return (string) $slider[$bnField];
        }

        return (string) ($slider[$field] ?? '');
    }

    if ($field === 'title_one') {
        return (string) ($slider->localized_title_one ?? $slider->title_one ?? '');
    }

    if ($field === 'title_two') {
        return (string) ($slider->localized_title_two ?? $slider->title_two ?? '');
    }

    return (string) ($slider->{$field} ?? '');
}

/**
 * Resolve selling unit price from product + selected variant items.
 * Variant item prices are full option prices (not added on top of base).
 */
function product_unit_price($product, $variants = null): float
{
    $base = 0.0;
    if ($product) {
        $offer = is_array($product) ? ($product['offer_price'] ?? null) : ($product->offer_price ?? null);
        $price = is_array($product) ? ($product['price'] ?? 0) : ($product->price ?? 0);
        $base = ($offer !== null && $offer !== '') ? (float) $offer : (float) $price;
    }

    if (! $variants) {
        return $base;
    }

    $variantTotal = 0.0;
    $hasVariantPrice = false;

    foreach ($variants as $variant) {
        $item = null;
        if (is_object($variant)) {
            $item = $variant->variantItem ?? $variant;
            if (isset($variant->variant_item_id) && ! isset($item->price)) {
                $item = \App\Models\ProductVariantItem::find($variant->variant_item_id);
            }
        } elseif (is_array($variant)) {
            if (isset($variant['variant_price'])) {
                $p = (float) $variant['variant_price'];
                if ($p > 0) {
                    $variantTotal += $p;
                    $hasVariantPrice = true;
                }
                continue;
            }
            $itemId = $variant['variant_item_id'] ?? null;
            if ($itemId) {
                $item = \App\Models\ProductVariantItem::find($itemId);
            }
        }

        if ($item && (float) ($item->price ?? 0) > 0) {
            $variantTotal += (float) $item->price;
            $hasVariantPrice = true;
        }
    }

    return $hasVariantPrice ? $variantTotal : $base;
}
