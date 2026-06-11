<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branding_edukasi', function (Blueprint $table) {
            $table->string('jenis_konten', 30)->default('branding')->after('nama_konten');
            $table->text('deskripsi_konten')->nullable()->after('jenis_konten');
            $table->string('link_konten', 500)->nullable()->after('video_konten');
        });
    }

    public function down(): void
    {
        Schema::table('branding_edukasi', function (Blueprint $table) {
            $table->dropColumn(['jenis_konten', 'deskripsi_konten', 'link_konten']);
        });
    }
};
