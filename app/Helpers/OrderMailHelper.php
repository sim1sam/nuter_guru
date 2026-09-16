<?php

namespace App\Helpers;

use App\Mail\OrderSuccessfully;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Setting;
use Throwable;

class OrderMailHelper
{
    /**
     * Notify store admins when a new order is placed (checkout or POS).
     */
    public static function notifyAdmin(Order $order, ?string $orderDetailsHtml = null): bool
    {
        $recipients = self::adminRecipients();
        if ($recipients === []) {
            return false;
        }

        try {
            $setting = Setting::first();
            $currency = $setting->currency_icon ?? '৳';
            $order->loadMissing(['orderProducts.product', 'orderAddress', 'user']);

            $customerName = $order->orderAddress->billing_name
                ?? optional($order->user)->name
                ?? 'Customer';
            $customerEmail = $order->orderAddress->billing_email
                ?? optional($order->user)->email
                ?? '';
            $customerPhone = $order->orderAddress->billing_phone
                ?? optional($order->user)->phone
                ?? '';

            if ($orderDetailsHtml === null || $orderDetailsHtml === '') {
                $orderDetailsHtml = self::buildOrderDetailsHtml($order, $currency);
            }

            $subject = 'New Order #'.($order->order_id ?? $order->id);
            $isPos = ! empty($order->is_pos);
            $source = $isPos ? 'POS' : 'Website';

            $message = '<p><strong>New order received ('.$source.')</strong></p>'
                .'<p>Order ID: <b>'.e($order->order_id ?? $order->id).'</b></p>'
                .'<p>Customer: <b>'.e($customerName).'</b></p>'
                .'<p>Email: <b>'.e($customerEmail).'</b></p>'
                .'<p>Phone: <b>'.e($customerPhone).'</b></p>'
                .'<p>Total: <b>'.e($currency).number_format((float) ($order->total_amount ?? $order->amount_real_currency ?? 0), 2).'</b></p>'
                .'<p>Payment: <b>'.e($order->payment_method ?? '').'</b> ('
                .(($order->payment_status == 1) ? 'Paid' : 'Pending').')</p>'
                .'<p>Shipping: <b>'.e($order->shipping_method ?? '').'</b> — '
                .e($currency).number_format((float) ($order->shipping_cost ?? 0), 2).'</p>'
                .'<p>Date: <b>'.optional($order->created_at)->format('d F, Y h:i A').'</b></p>'
                .'<hr><p><b>Items:</b></p>'.$orderDetailsHtml;

            return MailHelper::sendTo($recipients, new OrderSuccessfully($message, $subject));
        } catch (Throwable $e) {
            report($e);

            return false;
        }
    }

    public static function adminRecipients(): array
    {
        $emails = [];

        try {
            $setting = Setting::first();
            if ($setting) {
                foreach (['contact_email', 'topbar_email', 'email'] as $field) {
                    $value = trim((string) ($setting->{$field} ?? ''));
                    if ($value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $emails[] = $value;
                    }
                }
            }
        } catch (Throwable $e) {
            // ignore
        }

        try {
            foreach (Admin::query()->pluck('email') as $email) {
                $email = trim((string) $email);
                if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emails[] = $email;
                }
            }
        } catch (Throwable $e) {
            // ignore
        }

        return array_values(array_unique($emails));
    }

    protected static function buildOrderDetailsHtml(Order $order, string $currency): string
    {
        $rows = '';
        foreach ($order->orderProducts as $item) {
            $name = product_name($item->product) ?: ($item->product_name ?? 'Product');
            $qty = $item->qty ?? $item->quantity ?? 1;
            $price = (float) ($item->unit_price ?? $item->product_price ?? 0);
            $rows .= '<tr>'
                .'<td style="padding:4px 8px;border:1px solid #ddd;">'.e($name).'</td>'
                .'<td style="padding:4px 8px;border:1px solid #ddd;">'.e((string) $qty).'</td>'
                .'<td style="padding:4px 8px;border:1px solid #ddd;">'.e($currency).number_format($price, 2).'</td>'
                .'</tr>';
        }

        if ($rows === '') {
            return '<p>No line items.</p>';
        }

        return '<table style="border-collapse:collapse;width:100%;max-width:560px;">'
            .'<thead><tr>'
            .'<th style="padding:4px 8px;border:1px solid #ddd;text-align:left;">Product</th>'
            .'<th style="padding:4px 8px;border:1px solid #ddd;text-align:left;">Qty</th>'
            .'<th style="padding:4px 8px;border:1px solid #ddd;text-align:left;">Price</th>'
            .'</tr></thead><tbody>'.$rows.'</tbody></table>';
    }
}
