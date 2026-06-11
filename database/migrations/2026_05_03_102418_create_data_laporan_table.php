<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->integer('id_pelanggan');
            $table->integer('id_transaksi');
            $table->string('nama_biaya');
            $table->integer('jumlah_biaya');
            $table->string('keterangan_biaya');
            $table->string('metode_pembayaran');
            $table->string('status_transaksi');
            $table->date('tanggal_biaya');
            $table->integer('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_laporan');
    }
};
