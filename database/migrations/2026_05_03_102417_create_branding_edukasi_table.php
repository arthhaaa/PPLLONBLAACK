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
        Schema::create('branding_edukasi', function (Blueprint $table) {
            $table->id('id_konten');
            $table->string('username');
            $table->string('nama_mitra');
            $table->string('logo_mitra')->nullable();
            $table->string('nama_konten');
            $table->text('video_konten')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branding_edukasi');
    }
};
