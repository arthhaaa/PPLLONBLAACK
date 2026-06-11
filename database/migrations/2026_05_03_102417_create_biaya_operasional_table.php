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
        Schema::create('biaya_operasional', function (Blueprint $table) {
            $table->id('id_biaya');
            $table->string('username');
            $table->string('jenis_biaya');
            $table->integer('jumlah_biaya');
            $table->string('keterangan')->nullable();
            $table->string('nama_biaya');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_operasional');
    }
};
