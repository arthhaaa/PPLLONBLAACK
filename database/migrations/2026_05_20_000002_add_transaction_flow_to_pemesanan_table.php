<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            if (! Schema::hasColumn('pemesanan', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id_pesanan');
            }

            if (! Schema::hasColumn('pemesanan', 'kode_transaksi')) {
                $table->string('kode_transaksi')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('pemesanan', 'status_transaksi')) {
                $table->string('status_transaksi')->default('menunggu_pembayaran')->after('metode_pembayaran');
            }

            if (! Schema::hasColumn('pemesanan', 'alamat_pengiriman')) {
                $table->text('alamat_pengiriman')->nullable()->after('status_transaksi');
            }

            if (! Schema::hasColumn('pemesanan', 'catatan')) {
                $table->text('catatan')->nullable()->after('alamat_pengiriman');
            }

            if (! Schema::hasColumn('pemesanan', 'dibayar_pada')) {
                $table->timestamp('dibayar_pada')->nullable()->after('catatan');
            }

            if (! Schema::hasColumn('pemesanan', 'dibatalkan_pada')) {
                $table->timestamp('dibatalkan_pada')->nullable()->after('dibayar_pada');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            foreach ([
                'user_id',
                'kode_transaksi',
                'status_transaksi',
                'alamat_pengiriman',
                'catatan',
                'dibayar_pada',
                'dibatalkan_pada',
            ] as $column) {
                if (Schema::hasColumn('pemesanan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
