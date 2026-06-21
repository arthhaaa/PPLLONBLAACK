<?php

namespace Tests\Unit;

use App\Services\MidtransWebhookService;
use Tests\TestCase;

class MidtransWebhookServiceTest extends TestCase
{
    public function test_it_accepts_a_valid_midtrans_signature(): void
    {
        config(['services.midtrans.server_key' => 'SB-Mid-server-test']);

        $payload = [
            'order_id' => 'TRX-TEST-001',
            'status_code' => '200',
            'gross_amount' => '125000.00',
        ];
        $payload['signature_key'] = hash(
            'sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('services.midtrans.server_key')
        );

        $this->assertTrue(app(MidtransWebhookService::class)->hasValidSignature($payload));
    }

    public function test_it_rejects_an_invalid_midtrans_signature(): void
    {
        config(['services.midtrans.server_key' => 'SB-Mid-server-test']);

        $this->assertFalse(app(MidtransWebhookService::class)->hasValidSignature([
            'order_id' => 'TRX-TEST-001',
            'status_code' => '200',
            'gross_amount' => '125000.00',
            'signature_key' => 'invalid',
        ]));
    }

    public function test_it_maps_sandbox_payment_statuses_to_order_statuses(): void
    {
        $service = app(MidtransWebhookService::class);

        $this->assertSame('sedang_diproses', $service->statusUpdate([
            'transaction_status' => 'settlement',
        ])['status_transaksi']);
        $this->assertSame('menunggu_pembayaran', $service->statusUpdate([
            'transaction_status' => 'pending',
        ])['status_transaksi']);
        $this->assertSame('dibatalkan', $service->statusUpdate([
            'transaction_status' => 'expire',
        ])['status_transaksi']);
    }
}
