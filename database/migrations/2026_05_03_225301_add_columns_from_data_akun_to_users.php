<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->unique()->after('name');
            $table->string('nama')->nullable()->after('username');
            $table->text('alamat')->nullable()->after('nama');
            $table->string('telp', 20)->nullable()->after('alamat');
            $table->enum('role', ['admin', 'user', 'other'])->default('user')->after('telp');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'nama', 'alamat', 'telp', 'role']);
        });
    }
};