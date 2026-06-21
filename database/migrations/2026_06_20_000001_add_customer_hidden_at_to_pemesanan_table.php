<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            if (! Schema::hasColumn('pemesanan', 'customer_hidden_at')) {
                $table->timestamp('customer_hidden_at')->nullable()->after('dibatalkan_pada');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pemesanan', 'customer_hidden_at')) {
                $table->dropColumn('customer_hidden_at');
            }
        });
    }
};
