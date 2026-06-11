<?php

namespace Database\Seeders;

use App\Models\DataProduk;
use App\Models\DataAkun;
use App\Models\BiayaOperasional;
use App\Models\Pemesanan;
use App\Models\Transaksi;
use App\Models\TransaksiPenjualan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleBusinessSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'nama' => 'Administrator',
                'email' => 'admin@longblack.test',
                'password' => Hash::make('admin123'),
                'alamat' => 'Jember, Jawa Timur',
                'telp' => '081234567890',
                'role' => 'admin',
            ]
        );

        DataAkun::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'email' => 'admin@longblack.test',
                'password' => Hash::make('admin123'),
                'alamat' => 'Jember, Jawa Timur',
                'telp' => '081234567890',
                'role' => 'admin',
            ]
        );

        $customers = collect([
            ['username' => 'dina', 'name' => 'Dina Anggraini', 'email' => 'dina@example.test', 'telp' => '081211110001', 'alamat' => 'Kaliwates, Jember'],
            ['username' => 'rama', 'name' => 'Rama Pratama', 'email' => 'rama@example.test', 'telp' => '081211110002', 'alamat' => 'Sumbersari, Jember'],
            ['username' => 'salsa', 'name' => 'Salsa Putri', 'email' => 'salsa@example.test', 'telp' => '081211110003', 'alamat' => 'Patrang, Jember'],
            ['username' => 'bagas', 'name' => 'Bagas Mahendra', 'email' => 'bagas@example.test', 'telp' => '081211110004', 'alamat' => 'Ambulu, Jember'],
            ['username' => 'nabila', 'name' => 'Nabila Rahma', 'email' => 'nabila@example.test', 'telp' => '081211110005', 'alamat' => 'Ajung, Jember'],
            ['username' => 'reza', 'name' => 'Reza Kurniawan', 'email' => 'reza@example.test', 'telp' => '081211110006', 'alamat' => 'Tanggul, Jember'],
            ['username' => 'mei', 'name' => 'Mei Lestari', 'email' => 'mei@example.test', 'telp' => '081211110007', 'alamat' => 'Rambipuji, Jember'],
            ['username' => 'farhan', 'name' => 'Farhan Ardi', 'email' => 'farhan@example.test', 'telp' => '081211110008', 'alamat' => 'Puger, Jember'],
        ])->map(function (array $customer) {
            return User::updateOrCreate(
                ['username' => $customer['username']],
                [
                    ...$customer,
                    'nama' => $customer['name'],
                    'password' => Hash::make('password'),
                    'role' => 'user',
                ]
            );
        });

        $customers->each(function (User $customer) {
            DataAkun::updateOrCreate(
                ['username' => $customer->username],
                [
                    'nama' => $customer->name,
                    'email' => $customer->email,
                    'password' => Hash::make('password'),
                    'alamat' => $customer->alamat,
                    'telp' => $customer->telp,
                    'role' => 'pelanggan',
                ]
            );
        });

        $products = DataProduk::whereNotNull('gambar_produk')
            ->where('gambar_produk', '!=', '')
            ->oldest('id_produk')
            ->take(10)
            ->get();

        if ($products->isEmpty()) {
            $products = collect([
                ['nama_produk' => 'Long Black Arabica Argopuro', 'deskripsi_produk' => 'Kopi arabika dengan karakter citrus, floral, dan aftertaste manis.', 'harga_produk' => 85000, 'stok_produk' => 32],
                ['nama_produk' => 'Long Black Robusta Premium', 'deskripsi_produk' => 'Robusta bold untuk espresso dan susu dengan body tebal.', 'harga_produk' => 62000, 'stok_produk' => 45],
                ['nama_produk' => 'House Blend Signature', 'deskripsi_produk' => 'Blend arabika dan robusta untuk seduhan harian yang seimbang.', 'harga_produk' => 72000, 'stok_produk' => 28],
                ['nama_produk' => 'Cold Brew Blend', 'deskripsi_produk' => 'Racikan kopi untuk cold brew dengan rasa cokelat dan karamel.', 'harga_produk' => 78000, 'stok_produk' => 20],
                ['nama_produk' => 'Espresso Dark Roast', 'deskripsi_produk' => 'Roast gelap dengan crema pekat dan rasa kacang panggang.', 'harga_produk' => 69000, 'stok_produk' => 36],
                ['nama_produk' => 'Single Origin Jember Natural', 'deskripsi_produk' => 'Single origin proses natural dengan aroma buah matang.', 'harga_produk' => 92000, 'stok_produk' => 18],
            ])->map(function (array $product) {
                return DataProduk::updateOrCreate(
                    ['nama_produk' => $product['nama_produk']],
                    $product
                );
            })->values();
        }

        $statuses = ['selesai', 'sedang_diproses', 'siap_dikirim', 'menunggu_pembayaran', 'dibatalkan'];
        $paymentMethods = ['QRIS', 'QRIS', 'QRIS', 'QRIS'];

        collect(range(1, 24))->each(function (int $index) use ($customers, $products, $statuses, $paymentMethods) {
            $customer = $customers[($index - 1) % $customers->count()];
            $product = $products[($index - 1) % $products->count()];
            $quantity = ($index % 4) + 1;
            $subtotal = (int) $product->harga_produk * $quantity;
            $shipping = 10000 + (($index % 5) * 3000);
            $status = $statuses[($index - 1) % count($statuses)];
            $createdAt = Carbon::now()->subDays($index * 3)->setTime(9 + ($index % 8), 15, 0);
            $kodeTransaksi = 'TRX-DEMO-' . str_pad((string) $index, 4, '0', STR_PAD_LEFT);

            Pemesanan::updateOrCreate(
                [
                    'kode_transaksi' => $kodeTransaksi,
                    'id_produk' => $product->id_produk,
                ],
                [
                    'user_id' => $customer->id,
                    'username' => $customer->username,
                    'nama_produk' => $product->nama_produk,
                    'bentuk_produk' => $index % 3 === 0 ? 'bubuk' : 'biji',
                    'metode_pembayaran' => $paymentMethods[$index % count($paymentMethods)],
                    'status_transaksi' => $status,
                    'alamat_pengiriman' => $customer->alamat,
                    'catatan' => $index % 2 === 0 ? 'Mohon dikemas rapi.' : null,
                    'total_harga_produk' => $subtotal,
                    'total_produk' => $quantity,
                    'subtotal_produk' => $subtotal,
                    'ongkir' => $shipping,
                    'kurir' => 'jne',
                    'layanan_kurir' => 'REG',
                    'dibayar_pada' => in_array($status, ['sedang_diproses', 'siap_dikirim', 'selesai'], true) ? $createdAt->copy()->addMinutes(20) : null,
                    'dibatalkan_pada' => $status === 'dibatalkan' ? $createdAt->copy()->addHours(2) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );

            $order = Pemesanan::where('kode_transaksi', $kodeTransaksi)
                ->where('id_produk', $product->id_produk)
                ->first();

            $transaction = Transaksi::updateOrCreate(
                ['id_pesanan' => $order->id_pesanan],
                [
                    'id_produk' => $product->id_produk,
                    'nama_pesanan' => $product->nama_produk,
                    'jumlah_produk' => (string) $quantity,
                    'metode_pembayaran' => 'QRIS',
                    'tanggal_transaksi' => $createdAt->toDateString(),
                    'total_harga' => $subtotal + $shipping,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );

            TransaksiPenjualan::updateOrCreate(
                ['id_transaksi' => $transaction->id_transaksi],
                [
                    'id_pelanggan' => $customer->id,
                    'id_produk' => $product->id_produk,
                    'metode_pembayaran' => 'QRIS',
                    'status_transaksi' => $status,
                    'total_transaksi' => $subtotal + $shipping,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]
            );
        });

        collect([
            ['jenis_biaya' => 'Bahan Baku', 'nama_biaya' => 'Pembelian green bean Arabica', 'jumlah_biaya' => 320000, 'tanggal' => now()->subDays(28)->toDateString()],
            ['jenis_biaya' => 'Bahan Baku', 'nama_biaya' => 'Pembelian green bean Robusta', 'jumlah_biaya' => 240000, 'tanggal' => now()->subDays(24)->toDateString()],
            ['jenis_biaya' => 'Kemasan', 'nama_biaya' => 'Standing pouch dan label produk', 'jumlah_biaya' => 120000, 'tanggal' => now()->subDays(20)->toDateString()],
            ['jenis_biaya' => 'Operasional', 'nama_biaya' => 'Listrik dan air produksi', 'jumlah_biaya' => 90000, 'tanggal' => now()->subDays(16)->toDateString()],
            ['jenis_biaya' => 'Pemasaran', 'nama_biaya' => 'Konten promosi sosial media', 'jumlah_biaya' => 85000, 'tanggal' => now()->subDays(12)->toDateString()],
            ['jenis_biaya' => 'Logistik', 'nama_biaya' => 'Bahan packing pengiriman', 'jumlah_biaya' => 70000, 'tanggal' => now()->subDays(9)->toDateString()],
            ['jenis_biaya' => 'Perawatan', 'nama_biaya' => 'Perawatan grinder dan mesin', 'jumlah_biaya' => 150000, 'tanggal' => now()->subDays(6)->toDateString()],
            ['jenis_biaya' => 'Operasional', 'nama_biaya' => 'Gaji part-time produksi', 'jumlah_biaya' => 210000, 'tanggal' => now()->subDays(3)->toDateString()],
            ['jenis_biaya' => 'Bahan Baku', 'nama_biaya' => 'Restock biji kopi mingguan', 'jumlah_biaya' => 280000, 'tanggal' => now()->subDays(1)->toDateString()],
        ])->each(function (array $cost) use ($admin) {
            BiayaOperasional::updateOrCreate(
                [
                    'nama_biaya' => $cost['nama_biaya'],
                    'tanggal' => $cost['tanggal'],
                ],
                [
                    'username' => $admin->username,
                    'jenis_biaya' => $cost['jenis_biaya'],
                    'jumlah_biaya' => $cost['jumlah_biaya'],
                    'keterangan' => 'Data demo untuk memperlihatkan laba bersih pada laporan admin.',
                ]
            );
        });

        $this->command?->info('Sample akun, pemesanan, dan transaksi berhasil dibuat.');
        $this->command?->info('Admin demo: username admin / password admin123');
        $this->command?->info('Pelanggan demo: username dina / password password');
    }
}
