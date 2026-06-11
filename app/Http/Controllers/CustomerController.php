<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BrandingEdukasi;
use App\Models\DataProduk;
use App\Models\Pemesanan;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerController extends Controller
{

    public function home()
    {   
        $user = Auth::user();
        // Ambil beberapa produk terbaru dari model DataProduk
        $latestProducts = DataProduk::latest()->take(8)->get();
        // Ambil produk untuk ditampilkan di home
        $popularProducts = DataProduk::latest()->take(4)->get();
        $homepageBrandings = BrandingEdukasi::active()
            ->visibleFor('customer')
            ->orderBy('urutan')
            ->latest()
            ->take(12)
            ->get();
        
        return view('customer.home', compact('user', 'latestProducts', 'popularProducts', 'homepageBrandings'));
    }

    /**
     * Menampilkan daftar semua produk untuk customer
     */
    public function productIndex(Request $request)
    {
        $user = Auth::user();
        
        // Query produk dari model DataProduk
        $query = DataProduk::query();
        
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_produk', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi_produk', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('min_price')) {
            $query->whereRaw('CAST(harga_produk AS DECIMAL(12, 2)) >= ?', [(float) $request->min_price]);
        }

        if ($request->filled('max_price')) {
            $query->whereRaw('CAST(harga_produk AS DECIMAL(12, 2)) <= ?', [(float) $request->max_price]);
        }

        if ($request->stock === 'available') {
            $query->whereRaw('CAST(stok_produk AS UNSIGNED) > 0');
        } elseif ($request->stock === 'empty') {
            $query->whereRaw('CAST(stok_produk AS UNSIGNED) <= 0');
        }
        
        // Sorting - Sesuaikan dengan filter di view
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'termurah':
                    $query->orderByRaw('CAST(harga_produk AS DECIMAL(12, 2)) asc');
                    break;
                case 'termahal':
                    $query->orderByRaw('CAST(harga_produk AS DECIMAL(12, 2)) desc');
                    break;
                case 'terbaru':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $products = $query->paginate(12);
        
        // Tambahkan query string ke pagination
        $products->appends($request->all());
        
        return view('customer.produk.index', compact('user', 'products'));
    }
    /**
     * Menampilkan detail produk
     */
    public function showProduct($id)
    {
        $user = Auth::user();
        // Cari produk berdasarkan ID dari model DataProduk
        $product = DataProduk::findOrFail($id);
        
        // Ambil produk terkait (rekomendasi)
        $relatedProducts = DataProduk::where('id_produk', '!=', $product->id_produk)
                                     ->latest()
                                     ->limit(4)
                                     ->get();
        
        // ✅ PERBAIKAN 2: customer.produk.show (bukan customer.products.show)
        return view('customer.produk.show', compact('user', 'product', 'relatedProducts'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'telp'     => 'required|string|max:20',
            'alamat'   => 'required|string',
        ]);

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'telp'     => $request->telp,
            'alamat'   => $request->alamat,
        ]);

        return redirect()->route('customer.profile')
                         ->with('success', 'Profil berhasil diperbarui!');
    }

    public function orders()
    {
        $user = Auth::user();
        $transactions = $this->paginatedTransactions(
            Pemesanan::with('produk')->forUser($user)->latest()->get()
        );

        return view('customer.orders', compact('user', 'transactions'));
    }

    public function showOrder(string $kodeTransaksi)
    {
        $user = Auth::user();
        $items = $this->transactionItems($user, $kodeTransaksi);

        abort_if($items->isEmpty(), 404);

        $transaction = $this->summarizeTransaction($items);

        return view('customer.transaction-detail', compact('user', 'items', 'transaction'));
    }

    public function updateOrder(Request $request, string $kodeTransaksi)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string|in:QRIS',
            'alamat_pengiriman' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $items = $this->transactionItems($user, $kodeTransaksi);

        abort_if($items->isEmpty(), 404);

        if (! $items->first()->canBeModified()) {
            return back()->with('error', 'Transaksi tidak bisa diubah karena statusnya sudah berjalan.');
        }

        Pemesanan::whereIn('id_pesanan', $items->pluck('id_pesanan'))->update([
            'metode_pembayaran' => $request->metode_pembayaran,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route('customer.orders.show', $kodeTransaksi)
            ->with('success', 'Detail transaksi berhasil diperbarui.');
    }

    public function cancelOrder(string $kodeTransaksi)
    {
        $user = Auth::user();
        $items = $this->transactionItems($user, $kodeTransaksi);

        abort_if($items->isEmpty(), 404);

        if (! $items->first()->canBeModified()) {
            return back()->with('error', 'Transaksi tidak bisa dibatalkan karena statusnya sudah berjalan.');
        }

        Pemesanan::whereIn('id_pesanan', $items->pluck('id_pesanan'))->update([
            'status_transaksi' => 'dibatalkan',
            'dibatalkan_pada' => now(),
        ]);

        return redirect()
            ->route('customer.orders')
            ->with('success', 'Transaksi berhasil dibatalkan.');
    }

    public function tracking()
    {
        $user = Auth::user();
        $activeTransactions = Pemesanan::forUser($user)
            ->with('produk')
            ->whereNotIn('status_transaksi', ['selesai', 'dibatalkan'])
            ->latest()
            ->get()
            ->groupBy(fn ($order) => $order->kode_transaksi ?: 'ORDER-' . $order->id_pesanan)
            ->map(fn ($items) => $this->summarizeTransaction($items))
            ->values();

        $historyTransactions = Pemesanan::forUser($user)
            ->with('produk')
            ->whereIn('status_transaksi', ['selesai', 'dibatalkan'])
            ->latest()
            ->get()
            ->groupBy(fn ($order) => $order->kode_transaksi ?: 'ORDER-' . $order->id_pesanan)
            ->map(fn ($items) => $this->summarizeTransaction($items))
            ->values();

        return view('customer.tracking', compact('user', 'activeTransactions', 'historyTransactions'));
    }

    private function paginatedTransactions($orders): LengthAwarePaginator
    {
        $transactions = $orders
            ->groupBy(fn ($order) => $order->kode_transaksi ?: 'ORDER-' . $order->id_pesanan)
            ->map(fn ($items) => $this->summarizeTransaction($items))
            ->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;

        return new LengthAwarePaginator(
            $transactions->forPage($page, $perPage),
            $transactions->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function summarizeTransaction($items): array
    {
        $first = $items->first();

        return [
            'kode_transaksi' => $first->kode_transaksi ?: 'ORDER-' . $first->id_pesanan,
            'status_transaksi' => $first->status_transaksi ?? 'menunggu_pembayaran',
            'metode_pembayaran' => $first->metode_pembayaran,
            'alamat_pengiriman' => $first->alamat_pengiriman,
            'catatan' => $first->catatan,
            'ongkir' => (int) ($first->ongkir ?? 0),
            'kurir' => $first->kurir,
            'layanan_kurir' => $first->layanan_kurir,
            'qris_url' => $first->qris_url,
            'midtrans_order_id' => $first->midtrans_order_id,
            'midtrans_transaction_id' => $first->midtrans_transaction_id,
            'total_produk' => $items->sum('total_produk'),
            'subtotal_produk' => $items->sum('total_harga_produk'),
            'total_harga' => $items->sum('total_harga_produk') + (int) ($first->ongkir ?? 0),
            'jumlah_item' => $items->count(),
            'created_at' => $first->created_at,
            'items' => $items,
        ];
    }

    private function transactionItems($user, string $kodeTransaksi)
    {
        $query = Pemesanan::with('produk')->forUser($user)->oldest();

        if (str_starts_with($kodeTransaksi, 'ORDER-')) {
            return $query->where('id_pesanan', (int) str_replace('ORDER-', '', $kodeTransaksi))->get();
        }

        return $query->where('kode_transaksi', $kodeTransaksi)->get();
    }
}
