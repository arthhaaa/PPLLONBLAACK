<?php

namespace Database\Seeders;

use App\Models\DataAkun;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DataAkun::create([
            'nama'      => 'Administrator',
            'username'  => 'admin',
            'email'     => 'admin@longblack.com',
            'telp'      => '081234567890',
            'alamat'    => 'Jember, Jawa Timur',
            'password'  => Hash::make('admin123'),   // Password: admin123
            'role'      => 'admin',
        ]);

        $this->command->info('Admin berhasil dibuat!');
        $this->command->info('Username: admin');
        $this->command->info('Password: admin123');
    }
}