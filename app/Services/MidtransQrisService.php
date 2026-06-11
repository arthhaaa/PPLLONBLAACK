<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MidtransQrisService
{
    public function isConfigured(): bool
    {
        return filled(config('services.midtrans.server_key'));
    }

    /**
     * @throws RequestException
     */
    public function createCharge(array $transaction, array $items): array
    {
        $baseUrl = rtrim(config('services.midtrans.base_url'), '/');
        $serverKey = config('services.midtrans.server_key');

        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $transaction['order_id'],
                'gross_amount' => (int) $transaction['gross_amount'],
            ],
            'customer_details' => [
                'first_name' => $transaction['customer_name'],
                'email' => $transaction['customer_email'],
                'phone' => $transaction['customer_phone'],
            ],
            'item_details' => $items,
            'custom_expiry' => [
                'order_time' => now()->format('Y-m-d H:i:s O'),
                'expiry_duration' => (int) config('services.midtrans.expiry_duration', 60),
                'unit' => 'minute',
            ],
        ];

        $response = Http::withBasicAuth($serverKey, '')
            ->acceptJson()
            ->asJson()
            ->post($baseUrl . '/v2/charge', $payload)
            ->throw()
            ->json();

        return [
            'payload' => $payload,
            'response' => $response,
            'transaction_id' => $response['transaction_id'] ?? null,
            'qr_url' => $this->extractQrUrl($response),
        ];
    }

    private function extractQrUrl(array $response): ?string
    {
        foreach ($response['actions'] ?? [] as $action) {
            if (Str::contains($action['name'] ?? '', ['generate-qr-code', 'deeplink-redirect', 'get-qr-code'])) {
                return $action['url'] ?? null;
            }
        }

        return null;
    }
}
