<?php

namespace App\Http\Controllers;

use App\Services\MidtransQrisService;
use App\Services\RajaOngkirService;
use App\Models\DataProduk;
use App\Models\Pemesanan;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function __construct(
        private readonly MidtransQrisService $midtransQris,
        private readonly RajaOngkirService $rajaOngkir,
    ) {
    }

    public function index()
    {
        $cart = $this->normalizeCartQuantities(session('customer.cart', []));
        session(['customer.cart' => $cart]);

        return view('customer.cart');
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'bentuk_produk' => 'required|in:biji,bubuk',
        ]);

        $product = DataProduk::findOrFail($id);
        $stock = (int) $product->stok_produk;

        if ($stock <= 0) {
            return redirect()->back()->with('error', 'Stok produk sudah habis');
        }

        $quantity = (int) $request->input('quantity', 1);
        $bentukProduk = $request->input('bentuk_produk');
        $cartKey = $product->id_produk . '-' . $bentukProduk;

        $cart = session('customer.cart', []);
        $otherCartQty = $this->cartProductQuantity($cart, (int) $product->id_produk, $cartKey);
        $availableQty = max(0, $stock - $otherCartQty);

        if ($availableQty <= 0) {
            return redirect()->back()->with('error', 'Jumlah produk di keranjang sudah mencapai stok tersedia');
        }

        $quantity = max(1, min($quantity, $availableQty));

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['qty'] = min((int) $cart[$cartKey]['qty'] + $quantity, $availableQty);
            $cart[$cartKey]['stock'] = $stock;
        } else {
            $cart[$cartKey] = [
                'id_produk' => $product->id_produk,
                'name' => $product->nama_produk,
                'price' => (int) $product->harga_produk,
                'qty' => $quantity,
                'stock' => $stock,
                'bentuk_produk' => $bentukProduk,
                'image' => $product->gambar_produk,
            ];
        }

        $cart = $this->normalizeCartQuantities($cart);
        session(['customer.cart' => $cart]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function remove($id)
    {
        $cart = session('customer.cart', []);
        unset($cart[$id]);
        session(['customer.cart' => $cart]);

        return redirect()->route('customer.cart')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function update(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        $cart = $this->cartWithRequestedQuantities($request);

        session(['customer.cart' => $cart]);

        return redirect()->route('customer.cart')->with('success', 'Keranjang berhasil diperbarui');
    }

    public function shippingEstimate(Request $request)
    {
        $request->validate([
            'destination_city_id' => 'required|integer|min:1',
            'courier' => 'required|string|in:jne,pos,tiki',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'string',
            'quantities' => 'nullable|array',
            'quantities.*' => 'integer|min:1',
        ]);

        if (! $this->rajaOngkir->isConfigured()) {
            return response()->json([
                'message' => 'API RajaOngkir belum dikonfigurasi. Isi RAJAONGKIR_API_KEY dan RAJAONGKIR_ORIGIN_CITY_ID di .env.',
            ], 422);
        }

        $cart = $this->cartWithRequestedQuantities($request);
        $selectedCart = array_intersect_key($cart, array_flip($request->input('selected_items', [])));

        if (empty($selectedCart)) {
            return response()->json(['message' => 'Pilih minimal satu produk.'], 422);
        }

        $weight = collect($selectedCart)->sum(fn ($item) => (int) $item['qty'] * 250);

        try {
            $response = $this->rajaOngkir->cost(
                (int) $request->destination_city_id,
                max(1, $weight),
                $request->courier
            );
            $shipping = $this->rajaOngkir->cheapestRegularCost($response);
        } catch (RequestException $exception) {
            return response()->json([
                'message' => 'Gagal mengambil ongkir dari RajaOngkir.',
                'detail' => $exception->response?->json() ?: $exception->getMessage(),
            ], 422);
        }

        if (! $shipping) {
            return response()->json(['message' => 'Layanan ongkir tidak ditemukan.'], 422);
        }

        return response()->json([
            'courier' => strtolower($request->courier),
            'weight' => $weight,
            'service' => $shipping['service'],
            'description' => $shipping['description'],
            'cost' => $shipping['cost'],
            'etd' => $shipping['etd'],
        ]);
    }

    public function searchDestinations(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string|min:3|max:80',
        ]);

        if (! $this->rajaOngkir->hasApiKey()) {
            return response()->json([
                'message' => 'API RajaOngkir belum dikonfigurasi. Isi RAJAONGKIR_API_KEY di .env.',
            ], 422);
        }

        try {
            $response = $this->rajaOngkir->searchDomesticDestinations($validated['search']);
        } catch (RequestException $exception) {
            return response()->json([
                'message' => 'Gagal mencari tujuan RajaOngkir.',
                'detail' => $exception->response?->json() ?: $exception->getMessage(),
            ], 422);
        }

        $destinations = collect(data_get($response, 'data', []))
            ->map(fn ($destination) => [
                'id' => (int) data_get($destination, 'id'),
                'label' => data_get($destination, 'label'),
                'province_name' => data_get($destination, 'province_name'),
                'city_name' => data_get($destination, 'city_name'),
                'district_name' => data_get($destination, 'district_name'),
                'subdistrict_name' => data_get($destination, 'subdistrict_name'),
                'zip_code' => data_get($destination, 'zip_code'),
            ])
            ->filter(fn ($destination) => $destination['id'] > 0 && filled($destination['label']))
            ->values();

        return response()->json([
            'data' => $destinations,
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|string|in:QRIS',
            'alamat_pengiriman' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:500',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'string',
            'quantities' => 'nullable|array',
            'quantities.*' => 'integer|min:1',
            'destination_city_id' => 'nullable|integer|min:1',
            'courier' => 'nullable|string|in:jne,pos,tiki',
            'shipping_cost' => 'nullable|integer|min:0',
            'shipping_service' => 'nullable|string|max:100',
        ]);

        if (! $this->midtransQris->isConfigured()) {
            return redirect()->route('customer.cart')
                ->with('error', 'API Midtrans belum dikonfigurasi. Isi MIDTRANS_SERVER_KEY di .env sebelum checkout QRIS.');
        }

        $cart = $this->cartWithRequestedQuantities($request);

        if (empty($cart)) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang masih kosong');
        }

        $selectedKeys = $request->input('selected_items', []);
        $selectedCart = array_intersect_key($cart, array_flip($selectedKeys));

        if (empty($selectedCart)) {
            return redirect()->route('customer.cart')->with('error', 'Pilih minimal satu produk untuk checkout');
        }

        $user = Auth::user();
        $kodeTransaksi = 'TRX-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
        $productSubtotal = collect($selectedCart)->sum(fn ($item) => (int) $item['price'] * (int) $item['qty']);
        $shippingCost = (int) $request->input('shipping_cost', 0);
        $grandTotal = $productSubtotal + $shippingCost;

        $midtransItems = collect($selectedCart)
            ->map(fn ($item, $key) => [
                'id' => (string) ($item['id_produk'] ?? $key),
                'price' => (int) $item['price'],
                'quantity' => (int) $item['qty'],
                'name' => Str::limit($item['name'], 48, ''),
            ])
            ->values()
            ->all();

        if ($shippingCost > 0) {
            $midtransItems[] = [
                'id' => 'ONGKIR',
                'price' => $shippingCost,
                'quantity' => 1,
                'name' => 'Ongkir ' . strtoupper((string) $request->courier),
            ];
        }

        try {
            $charge = $this->midtransQris->createCharge([
                'order_id' => $kodeTransaksi,
                'gross_amount' => $grandTotal,
                'customer_name' => $user->name ?? $user->username,
                'customer_email' => $user->email,
                'customer_phone' => $user->telp,
            ], $midtransItems);
        } catch (RequestException $exception) {
            return redirect()->route('customer.cart')
                ->with('error', 'Gagal membuat pembayaran QRIS Midtrans: ' . ($exception->response?->json('status_message') ?: $exception->getMessage()));
        }

        foreach ($selectedCart as $item) {
            $itemSubtotal = (int) $item['price'] * (int) $item['qty'];
            Pemesanan::create([
                'user_id' => $user->id,
                'kode_transaksi' => $kodeTransaksi,
                'id_produk' => $item['id_produk'],
                'username' => $user->username ?? $user->name,
                'nama_produk' => $item['name'],
                'bentuk_produk' => $item['bentuk_produk'] ?? 'biji',
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_transaksi' => 'menunggu_pembayaran',
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'catatan' => $request->catatan,
                'total_harga_produk' => $itemSubtotal,
                'total_produk' => (int) $item['qty'],
                'subtotal_produk' => $productSubtotal,
                'ongkir' => $shippingCost,
                'kurir' => $request->courier,
                'layanan_kurir' => $request->shipping_service,
                'destination_city_id' => $request->destination_city_id,
                'midtrans_order_id' => $kodeTransaksi,
                'midtrans_transaction_id' => $charge['transaction_id'],
                'qris_url' => $charge['qr_url'],
                'payment_payload' => $charge['payload'],
                'payment_response' => $charge['response'],
            ]);
        }

        $remainingCart = array_diff_key($cart, $selectedCart);

        if (empty($remainingCart)) {
            session()->forget('customer.cart');
        } else {
            session(['customer.cart' => $remainingCart]);
        }

        return redirect()
            ->route('customer.orders.show', $kodeTransaksi)
            ->with('success', 'Transaksi QRIS berhasil dibuat. Silakan scan QRIS pada detail pesanan.');
    }

    private function cartWithRequestedQuantities(Request $request): array
    {
        $cart = session('customer.cart', []);

        foreach ($request->input('quantities', []) as $key => $quantity) {
            if (isset($cart[$key])) {
                $cart[$key]['qty'] = max(1, (int) $quantity);
            }
        }

        return $this->normalizeCartQuantities($cart);
    }

    private function normalizeCartQuantities(array $cart): array
    {
        $usedByProduct = [];

        foreach ($cart as $key => $item) {
            $productId = (int) ($item['id_produk'] ?? 0);
            $product = $productId > 0 ? DataProduk::find($productId) : null;

            if (! $product) {
                unset($cart[$key]);
                continue;
            }

            $stock = max(0, (int) $product->stok_produk);
            $used = $usedByProduct[$productId] ?? 0;
            $remaining = max(0, $stock - $used);
            $qty = min(max(1, (int) ($item['qty'] ?? 1)), $remaining);

            if ($stock <= 0 || $qty <= 0) {
                unset($cart[$key]);
                continue;
            }

            $cart[$key]['name'] = $product->nama_produk;
            $cart[$key]['price'] = (int) $product->harga_produk;
            $cart[$key]['image'] = $product->gambar_produk;
            $cart[$key]['stock'] = $stock;
            $cart[$key]['qty'] = $qty;
            $cart[$key]['max_qty'] = $remaining;

            $usedByProduct[$productId] = $used + $qty;
        }

        return $cart;
    }

    private function cartProductQuantity(array $cart, int $productId, ?string $exceptKey = null): int
    {
        return collect($cart)
            ->reject(fn ($item, $key) => $exceptKey !== null && $key === $exceptKey)
            ->filter(fn ($item) => (int) ($item['id_produk'] ?? 0) === $productId)
            ->sum(fn ($item) => (int) ($item['qty'] ?? 0));
    }
}
