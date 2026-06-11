<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branding_edukasi', function (Blueprint $table) {
            $table->unsignedSmallInteger('urutan')->default(0)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('branding_edukasi', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
};
