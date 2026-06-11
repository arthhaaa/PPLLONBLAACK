<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            if (! Schema::hasColumn('pemesanan', 'bentuk_produk')) {
                $table->string('bentuk_produk')->default('biji')->after('nama_produk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanan', 'bentuk_produk')) {
                $table->dropColumn('bentuk_produk');
            }
        });
    }
};
