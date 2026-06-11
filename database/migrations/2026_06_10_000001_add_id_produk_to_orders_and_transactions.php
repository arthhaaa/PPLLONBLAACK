<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            if (! Schema::hasColumn('pemesanan', 'id_produk')) {
                $table->unsignedBigInteger('id_produk')->nullable()->after('kode_transaksi')->index();
            }
        });

        Schema::table('transaksi', function (Blueprint $table) {
            if (! Schema::hasColumn('transaksi', 'id_produk')) {
                $table->unsignedBigInteger('id_produk')->nullable()->after('id_pesanan')->index();
            }
        });

        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            if (! Schema::hasColumn('transaksi_penjualan', 'id_produk')) {
                $table->unsignedBigInteger('id_produk')->nullable()->after('id_transaksi')->index();
            }
        });

        $products = DB::table('data_produk')->pluck('id_produk', 'nama_produk');

        DB::table('pemesanan')
            ->whereNull('id_produk')
            ->orderBy('id_pesanan')
            ->get(['id_pesanan', 'nama_produk'])
            ->each(function ($order) use ($products) {
                if ($products->has($order->nama_produk)) {
                    DB::table('pemesanan')
                        ->where('id_pesanan', $order->id_pesanan)
                        ->update(['id_produk' => $products[$order->nama_produk]]);
                }
            });

        DB::table('transaksi')
            ->whereNull('id_produk')
            ->orderBy('id_transaksi')
            ->get(['id_transaksi', 'nama_pesanan'])
            ->each(function ($transaction) use ($products) {
                if ($products->has($transaction->nama_pesanan)) {
                    DB::table('transaksi')
                        ->where('id_transaksi', $transaction->id_transaksi)
                        ->update(['id_produk' => $products[$transaction->nama_pesanan]]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('transaksi_penjualan', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_penjualan', 'id_produk')) {
                $table->dropColumn('id_produk');
            }
        });

        Schema::table('transaksi', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi', 'id_produk')) {
                $table->dropColumn('id_produk');
            }
        });

        Schema::table('pemesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanan', 'id_produk')) {
                $table->dropColumn('id_produk');
            }
        });
    }
};
