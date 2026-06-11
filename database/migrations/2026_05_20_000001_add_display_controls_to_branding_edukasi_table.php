<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branding_edukasi', function (Blueprint $table) {
            $table->string('tampil_di', 20)->default('both')->after('video_konten');
            $table->boolean('is_active')->default(true)->after('tampil_di');
        });
    }

    public function down(): void
    {
        Schema::table('branding_edukasi', function (Blueprint $table) {
            $table->dropColumn(['tampil_di', 'is_active']);
        });
    }
};
