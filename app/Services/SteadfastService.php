<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderProduct;
use App\Models\SteadfastSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SteadfastService
{
    public function createConsignment(Order $order): array
    {
        $settings = SteadfastSetting::current();
        if (! $settings->isConfigured()) {
            return ['success' => false, 'message' => 'Steadfast is not configured or disabled.'];
        }

        if ($order->steadfast_consignment_id) {
            return [
                'success' => true,
                'message' => 'Already sent to Steadfast.',
                'already_sent' => true,
                'consignment_id' => $order->steadfast_consignment_id,
                'tracking_code' => $order->steadfast_tracking_code,
            ];
        }

        $payload = $this->buildPayload($order);
        if (isset($payload['error'])) {
            return ['success' => false, 'message' => $payload['error']];
        }

        $baseUrl = rtrim($settings->base_url ?: 'https://portal.packzy.com/api/v1', '/');

        try {
            $response = Http::withHeaders([
                'Api-Key' => $settings->api_key,
                'Secret-Key' => $settings->secret_key,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($baseUrl.'/create_order', $payload);

            $body = $response->json() ?? [];
            $order->steadfast_response = json_encode($body);

            if ($response->successful() && (int) ($body['status'] ?? 0) === 200) {
                $consignment = $body['consignment'] ?? [];
                $order->steadfast_consignment_id = (string) ($consignment['consignment_id'] ?? '');
                $order->steadfast_tracking_code = (string) ($consignment['tracking_code'] ?? '');
                $order->steadfast_status = (string) ($consignment['status'] ?? 'in_review');
                $order->save();

                return [
                    'success' => true,
                    'message' => $body['message'] ?? 'Consignment created on Steadfast.',
                    'consignment_id' => $order->steadfast_consignment_id,
                    'tracking_code' => $order->steadfast_tracking_code,
                ];
            }

            $message = $body['message']
                ?? (is_array($body['errors'] ?? null) ? json_encode($body['errors']) : null)
                ?? ('Steadfast API error HTTP '.$response->status());

            $order->steadfast_status = 'failed';
            $order->save();

            Log::warning('Steadfast create_order failed', [
                'order_id' => $order->order_id,
                'status' => $response->status(),
                'body' => $body,
            ]);

            return ['success' => false, 'message' => $message, 'response' => $body];
        } catch (\Throwable $e) {
            Log::error('Steadfast create_order exception: '.$e->getMessage(), [
                'order_id' => $order->order_id,
            ]);
            $order->steadfast_status = 'failed';
            $order->steadfast_response = $e->getMessage();
            $order->save();

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function buildPayload(Order $order): array
    {
        $address = OrderAddress::where('order_id', $order->id)->first();
        if (! $address) {
            return ['error' => 'Order address not found.'];
        }

        $name = trim($address->shipping_name ?: $address->billing_name ?: '');
        $phone = $this->normalizePhone($address->shipping_phone ?: $address->billing_phone);
        $fullAddress = trim($address->shipping_address ?: $address->billing_address ?: '');
        if ($address->shipping_city || $address->billing_city) {
            $fullAddress = trim($fullAddress.', '.($address->shipping_city ?: $address->billing_city));
        }

        if ($name === '' || $phone === '' || $fullAddress === '') {
            return ['error' => 'Missing recipient name, phone, or address.'];
        }

        if (strlen($phone) !== 11) {
            return ['error' => 'Recipient phone must be 11 digits for Steadfast. Got: '.$phone];
        }

        $invoice = preg_replace('/[^A-Za-z0-9_-]/', '-', (string) $order->order_id);
        $invoice = trim($invoice, '-_') ?: ('ORD-'.$order->id);

        // Paid orders: COD 0; unpaid / COD: collect remaining
        $codAmount = ((int) $order->payment_status === 1) ? 0 : (float) $order->total_amount;

        $itemDesc = OrderProduct::where('order_id', $order->id)
            ->get()
            ->map(function ($p) {
                $name = $p->product_name ?? 'Item';
                $qty = $p->qty ?? 1;

                return $name.' x'.$qty;
            })
            ->implode(', ');

        return [
            'invoice' => substr($invoice, 0, 50),
            'recipient_name' => substr($name, 0, 100),
            'recipient_phone' => $phone,
            'recipient_address' => substr($fullAddress, 0, 250),
            'cod_amount' => round(max(0, $codAmount), 2),
            'note' => substr('Order '.$order->order_id, 0, 480),
            'item_description' => substr($itemDesc, 0, 250),
            'delivery_type' => 0,
        ];
    }

    public function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?: '';

        // 8801XXXXXXXXX → 01XXXXXXXXX
        if (str_starts_with($digits, '880') && strlen($digits) >= 13) {
            $digits = '0'.substr($digits, 3);
        }

        // 1XXXXXXXXX (10 digits) → 01XXXXXXXXX
        if (strlen($digits) === 10 && str_starts_with($digits, '1')) {
            $digits = '0'.$digits;
        }

        return $digits;
    }
}
