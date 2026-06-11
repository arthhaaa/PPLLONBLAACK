<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            if (! Schema::hasColumn('pemesanan', 'subtotal_produk')) {
                $table->integer('subtotal_produk')->default(0)->after('total_produk');
            }

            if (! Schema::hasColumn('pemesanan', 'ongkir')) {
                $table->integer('ongkir')->default(0)->after('subtotal_produk');
            }

            if (! Schema::hasColumn('pemesanan', 'kurir')) {
                $table->string('kurir', 20)->nullable()->after('ongkir');
            }

            if (! Schema::hasColumn('pemesanan', 'layanan_kurir')) {
                $table->string('layanan_kurir')->nullable()->after('kurir');
            }

            if (! Schema::hasColumn('pemesanan', 'destination_city_id')) {
                $table->unsignedInteger('destination_city_id')->nullable()->after('layanan_kurir');
            }

            if (! Schema::hasColumn('pemesanan', 'midtrans_order_id')) {
                $table->string('midtrans_order_id')->nullable()->after('destination_city_id');
            }

            if (! Schema::hasColumn('pemesanan', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            }

            if (! Schema::hasColumn('pemesanan', 'qris_url')) {
                $table->text('qris_url')->nullable()->after('midtrans_transaction_id');
            }

            if (! Schema::hasColumn('pemesanan', 'payment_payload')) {
                $table->json('payment_payload')->nullable()->after('qris_url');
            }

            if (! Schema::hasColumn('pemesanan', 'payment_response')) {
                $table->json('payment_response')->nullable()->after('payment_payload');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            foreach ([
                'subtotal_produk',
                'ongkir',
                'kurir',
                'layanan_kurir',
                'destination_city_id',
                'midtrans_order_id',
                'midtrans_transaction_id',
                'qris_url',
                'payment_payload',
                'payment_response',
            ] as $column) {
                if (Schema::hasColumn('pemesanan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
