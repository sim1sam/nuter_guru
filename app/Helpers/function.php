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
