@extends('layouts.customer')

@section('title', 'Keranjang Belanja - Long Black')

@section('content')
@php
    $cart = session('customer.cart', []);
    $grandTotal = collect($cart)->sum(fn ($item) => (int) $item['price'] * (int) $item['qty']);
@endphp

<section class="cart-hero">
    <div class="container">
        <div class="cart-hero__content">
            <h1>Shopping Cart</h1>
            <p>Home / Shopping Cart</p>
        </div>
    </div>
</section>

<section class="cart-page section_gap">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(count($cart) > 0)
            <form id="customer-cart-form" action="{{ route('customer.cart.update') }}" method="POST">
                @csrf
                <div class="cart-layout">
                    <div class="cart-products cart-products-modern">
                        <div class="cart-panel-title">
                            <div>
                                <span>Keranjang</span>
                                <h2>Produk Pilihan</h2>
                            </div>
                            <strong>{{ count($cart) }} item</strong>
                        </div>
                        <div class="cart-table-head cart-table-head-minimal">
                            <span>Product Code</span>
                            <span>Quantity</span>
                            <span>Total</span>
                            <span>Action</span>
                        </div>

                        <div class="cart-list">
                            @foreach($cart as $key => $item)
                                @php
                                    $subtotal = (int) $item['price'] * (int) $item['qty'];
                                    $imageUrl = !empty($item['image']) ? Storage::url($item['image']) : asset('img/product/p1.jpg');
                                @endphp
                                <div class="cart-row cart-row-minimal" data-cart-row data-price="{{ (int) $item['price'] }}">
                                    <input type="hidden" name="selected_items[]" value="{{ $key }}" class="cart-select" checked>

                                    <div class="cart-product-info">
                                        <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}">
                                        <div>
                                            <h4>{{ $item['name'] }}</h4>
                                            <p>Set : Bentuk {{ ucfirst($item['bentuk_produk'] ?? 'biji') }}</p>
                                        </div>
                                    </div>

                                    <div class="cart-quantity">
                                        <button type="button" class="qty-btn" data-qty-plus aria-label="Tambah jumlah">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        <input type="number"
                                               name="quantities[{{ $key }}]"
                                               class="cart-qty-input"
                                               min="1"
                                               value="{{ $item['qty'] }}">
                                        <button type="button" class="qty-btn" data-qty-minus aria-label="Kurangi jumlah">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>

                                    <div class="cart-subtotal" data-subtotal>
                                        Rp {{ number_format((float) $subtotal, 0, ',', '.') }}
                                    </div>

                                    <button type="submit"
                                            class="cart-remove-btn"
                                            formaction="{{ route('customer.cart.remove', $key) }}"
                                            formmethod="POST"
                                            formnovalidate
                                            aria-label="Hapus {{ $item['name'] }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <div class="cart-actions">
                            <button type="submit" class="cart-outline-btn" formnovalidate>Update Cart</button>
                            <a href="{{ route('customer.product.index') }}" class="cart-link-btn">Lanjut Belanja</a>
                        </div>
                    </div>

                    <aside class="order-summary cart-summary-minimal">
                        <h3>Order Summery</h3>
                        <div class="voucher-row">
                            <input type="text" class="form-control" placeholder="Discount voucher" disabled>
                            <button type="button" disabled>Apply</button>
                        </div>
                        <div class="summary-line">
                            <span>Selected Items</span>
                            <strong data-selected-count>{{ count($cart) }}</strong>
                        </div>
                        <div class="summary-line">
                            <span>Sub Total</span>
                            <strong data-selected-total>Rp {{ number_format((float) $grandTotal, 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-line">
                            <span>Shipping</span>
                            <strong data-shipping-total>Rp 0</strong>
                        </div>
                        <div class="summary-line">
                            <span>Discount</span>
                            <strong>- Rp 0</strong>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <strong data-selected-grand-total>Rp {{ number_format((float) $grandTotal, 0, ',', '.') }}</strong>
                        </div>

                        <div class="cart-warranty-note">
                            <i class="fa fa-shield"></i>
                            <span>Pesanan QRIS diproses melalui Midtrans. Ongkir dapat dihitung via RajaOngkir.</span>
                        </div>

                        <div class="checkout-panel cart-api-panel">
                            <input type="hidden" name="metode_pembayaran" value="QRIS">
                            <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                            <input type="hidden" name="shipping_service" id="shipping_service" value="">

                            <div class="qris-payment-card">
                                <span class="qris-payment-icon"><i class="fa fa-qrcode"></i></span>
                                <div>
                                    <small>Metode Pembayaran</small>
                                    <strong>QRIS Midtrans</strong>
                                    <p>Setelah checkout, sistem membuat QRIS melalui API Midtrans.</p>
                                </div>
                            </div>

                            <div class="shipping-estimator">
                                <div class="shipping-estimator__head">
                                    <div>
                                        <small>API RajaOngkir</small>
                                        <strong>Estimasi Ongkir</strong>
                                    </div>
                                    <span data-shipping-status>Belum dihitung</span>
                                </div>

                                <label for="destination_search">Kota / Kecamatan Tujuan</label>
                                <input type="hidden"
                                       name="destination_city_id"
                                       id="destination_city_id"
                                       value="">
                                <div class="destination-search-row">
                                    <input type="text"
                                           id="destination_search"
                                           class="form-control"
                                           placeholder="Contoh: Jember, Sumbersari, 68121"
                                           autocomplete="off">
                                    <button type="button" class="destination-search-btn" data-search-destination>
                                        Cari
                                    </button>
                                </div>
                                <div class="destination-result-list" data-destination-results></div>
                                <p class="shipping-help-text" data-selected-destination>Belum ada tujuan dipilih.</p>

                                <label for="courier">Kurir</label>
                                <select name="courier" id="courier" class="form-control">
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                </select>

                                <button type="button" class="shipping-estimate-btn" data-estimate-shipping>
                                    <i class="fa fa-truck"></i>
                                    <span>Cek Ongkir</span>
                                </button>
                                <p class="shipping-help-text">Pilih tujuan dari hasil pencarian sebelum mengecek ongkir.</p>
                            </div>

                            <label for="alamat_pengiriman">Alamat Pengiriman</label>
                            <textarea name="alamat_pengiriman" id="alamat_pengiriman" class="form-control" rows="3" required>{{ Auth::user()->alamat ?? '' }}</textarea>

                            <label for="catatan">Catatan Pesanan</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="2" placeholder="Contoh: kirim sore hari"></textarea>
                        </div>

                        <button type="submit"
                                formaction="{{ route('customer.cart.checkout') }}"
                                class="checkout-btn">
                            Checkout Now
                        </button>
                    </aside>
                </div>
            </form>
        @else
            <div class="text-center py-5">
                <h4>Keranjang Anda kosong</h4>
                <a href="{{ route('customer.product.index') }}" class="btn btn-primary mt-3">Belanja Sekarang</a>
            </div>
        @endif
    </div>
</section>

@endsection

@section('styles')
<style>
    .cart-hero {
        margin-top: 80px;
        padding: 78px 0;
        background: #f4f1ee;
        text-align: center;
    }

    .cart-hero__content h1 {
        color: #161616;
        font-size: 42px;
        font-weight: 800;
        margin-bottom: 14px;
    }

    .cart-hero__content p {
        color: #4b2e2b;
        font-weight: 600;
        margin: 0;
    }

    .cart-page {
        background: #fff;
    }

    .cart-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 32px;
        align-items: start;
    }

    .cart-table-head,
    .cart-row {
        display: grid;
        grid-template-columns: 70px minmax(260px, 1.5fr) 120px 160px 140px;
        align-items: center;
        column-gap: 18px;
    }

    .cart-table-head {
        background: #c88a43;
        color: #fff;
        min-height: 56px;
        padding: 0 22px;
        font-weight: 800;
    }

    .cart-row {
        padding: 24px 22px;
        border-bottom: 1px solid rgba(75, 46, 43, 0.12);
    }

    .cart-check {
        width: 26px;
        height: 26px;
        margin: 0;
        cursor: pointer;
    }

    .cart-check input {
        display: none;
    }

    .cart-check span {
        display: block;
        width: 26px;
        height: 26px;
        border: 2px solid #4b2e2b;
        border-radius: 4px;
        background: #fff;
        position: relative;
    }

    .cart-check input:checked + span {
        background: #4b210c;
        border-color: #4b210c;
    }

    .cart-check input:checked + span::after {
        content: "\f00c";
        font-family: FontAwesome;
        color: #fff;
        font-size: 14px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .cart-product-info {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .cart-product-info img {
        width: 92px;
        height: 92px;
        object-fit: cover;
        background: #f7f1eb;
        flex: 0 0 auto;
    }

    .cart-product-info h4 {
        color: #181818;
        font-size: 17px;
        font-weight: 800;
        margin: 0 0 7px;
    }

    .cart-product-info p {
        color: #8a817b;
        margin: 0 0 8px;
    }

    .cart-type-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 5px 10px;
        background: rgba(75, 46, 43, 0.1);
        color: #4b2e2b;
        font-weight: 800;
        font-size: 11px;
    }

    .cart-type-badge.is-powder {
        background: rgba(73, 169, 137, 0.16);
        color: #2e8b70;
    }

    .cart-price,
    .cart-subtotal {
        color: #181818;
        font-weight: 700;
        white-space: nowrap;
    }

    .cart-quantity {
        display: grid;
        grid-template-columns: 44px 58px 44px;
        height: 44px;
        border: 1px solid rgba(75, 46, 43, 0.14);
    }

    .qty-btn,
    .cart-qty-input {
        border: 0;
        background: #fff;
        color: #191919;
        text-align: center;
        font-weight: 700;
    }

    .qty-btn {
        cursor: pointer;
    }

    .cart-qty-input {
        border-left: 1px solid rgba(75, 46, 43, 0.1);
        border-right: 1px solid rgba(75, 46, 43, 0.1);
        width: 100%;
    }

    .cart-qty-input::-webkit-outer-spin-button,
    .cart-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .cart-actions {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-top: 28px;
    }

    .cart-link-btn,
    .cart-outline-btn,
    .checkout-btn {
        min-height: 52px;
        border-radius: 0;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 26px;
        cursor: pointer;
    }

    .cart-link-btn {
        border: 1px solid rgba(75, 46, 43, 0.16);
        color: #4b210c;
        background: #fff;
    }

    .cart-outline-btn {
        border: 1px solid #4b210c;
        color: #4b210c;
        background: #fff;
    }

    .order-summary {
        border: 1px solid rgba(75, 46, 43, 0.14);
        padding: 28px;
        background: #fff;
    }

    .order-summary h3 {
        color: #181818;
        font-size: 19px;
        font-weight: 800;
        padding-bottom: 20px;
        margin-bottom: 18px;
        border-bottom: 1px solid rgba(75, 46, 43, 0.1);
    }

    .summary-line,
    .summary-total {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        color: #8a817b;
        margin-bottom: 16px;
    }

    .summary-line strong,
    .summary-total strong {
        color: #181818;
        font-weight: 800;
        white-space: nowrap;
    }

    .summary-total {
        border-top: 1px solid rgba(75, 46, 43, 0.1);
        padding-top: 18px;
        margin-top: 4px;
        color: #4b2e2b;
        font-weight: 800;
    }

    .checkout-panel {
        border-top: 1px solid rgba(75, 46, 43, 0.1);
        padding-top: 20px;
        margin-top: 20px;
    }

    .checkout-panel label {
        color: #4b2e2b;
        font-weight: 700;
        margin-bottom: 8px;
        margin-top: 12px;
    }

    .checkout-panel .form-control {
        border-radius: 0;
        border-color: rgba(75, 46, 43, 0.16);
    }

    .checkout-btn {
        width: 100%;
        margin-top: 24px;
        border: 0;
        background: #4b210c;
        color: #fff;
    }

    .checkout-btn:disabled {
        background: #b4a79f;
        cursor: not-allowed;
    }

    @media (max-width: 1199px) {
        .cart-layout {
            grid-template-columns: 1fr;
        }

        .cart-table-head,
        .cart-row {
            grid-template-columns: 56px minmax(240px, 1fr) 110px 150px 130px;
        }
    }

    @media (max-width: 767px) {
        .cart-hero {
            padding: 58px 0;
        }

        .cart-hero__content h1 {
            font-size: 32px;
        }

        .cart-table-head {
            display: none;
        }

        .cart-row {
            grid-template-columns: 42px minmax(0, 1fr);
            gap: 14px;
            padding: 20px 0;
        }

        .cart-product-info,
        .cart-price,
        .cart-quantity,
        .cart-subtotal {
            grid-column: 2;
        }

        .cart-product-info img {
            width: 78px;
            height: 78px;
        }

        .cart-price::before {
            content: "Price: ";
            color: #8a817b;
            font-weight: 600;
        }

        .cart-subtotal::before {
            content: "Subtotal: ";
            color: #8a817b;
            font-weight: 600;
        }

        .cart-quantity {
            width: 146px;
        }

        .cart-actions {
            flex-direction: column;
        }

        .cart-link-btn,
        .cart-outline-btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formatter = new Intl.NumberFormat('id-ID');
        const rows = Array.from(document.querySelectorAll('[data-cart-row]'));
        const selectedCount = document.querySelector('[data-selected-count]');
        const selectedTotal = document.querySelector('[data-selected-total]');
        const selectedGrandTotal = document.querySelector('[data-selected-grand-total]');
        const shippingTotal = document.querySelector('[data-shipping-total]');
        const shippingInput = document.getElementById('shipping_cost');
        const shippingServiceInput = document.getElementById('shipping_service');
        const shippingStatus = document.querySelector('[data-shipping-status]');
        const estimateButton = document.querySelector('[data-estimate-shipping]');
        const destinationSearchInput = document.getElementById('destination_search');
        const destinationIdInput = document.getElementById('destination_city_id');
        const destinationSearchButton = document.querySelector('[data-search-destination]');
        const destinationResults = document.querySelector('[data-destination-results]');
        const selectedDestination = document.querySelector('[data-selected-destination]');
        const checkoutBtn = document.querySelector('.checkout-btn');

        if (!checkoutBtn) {
            return;
        }

        function formatRupiah(value) {
            return 'Rp ' + formatter.format(value);
        }

        function selectedKeys() {
            return rows
                .map(function (row) {
                    return row.querySelector('.cart-select').value;
                });
        }

        function refreshSummary() {
            let count = 0;
            let total = 0;
            const shipping = parseInt(shippingInput.value, 10) || 0;

            rows.forEach(function (row) {
                const qtyInput = row.querySelector('.cart-qty-input');
                const subtotalEl = row.querySelector('[data-subtotal]');
                const price = parseInt(row.dataset.price, 10) || 0;
                const qty = Math.max(1, parseInt(qtyInput.value, 10) || 1);
                const subtotal = price * qty;

                qtyInput.value = qty;
                subtotalEl.textContent = formatRupiah(subtotal);

                count += 1;
                total += subtotal;
            });

            selectedCount.textContent = count;
            selectedTotal.textContent = formatRupiah(total);
            shippingTotal.textContent = formatRupiah(count > 0 ? shipping : 0);
            selectedGrandTotal.textContent = formatRupiah(total + (count > 0 ? shipping : 0));
            checkoutBtn.disabled = count === 0;
        }

        rows.forEach(function (row) {
            const minusBtn = row.querySelector('[data-qty-minus]');
            const plusBtn = row.querySelector('[data-qty-plus]');
            const qtyInput = row.querySelector('.cart-qty-input');

            minusBtn.addEventListener('click', function () {
                qtyInput.value = Math.max(1, (parseInt(qtyInput.value, 10) || 1) - 1);
                refreshSummary();
            });

            plusBtn.addEventListener('click', function () {
                qtyInput.value = (parseInt(qtyInput.value, 10) || 1) + 1;
                refreshSummary();
            });

            qtyInput.addEventListener('input', refreshSummary);
        });

        function resetDestinationSelection(message) {
            destinationIdInput.value = '';
            shippingInput.value = 0;
            shippingServiceInput.value = '';
            selectedDestination.textContent = message || 'Belum ada tujuan dipilih.';
            refreshSummary();
        }

        function renderDestinations(destinations) {
            destinationResults.innerHTML = '';

            if (!destinations.length) {
                destinationResults.innerHTML = '<div class="destination-empty">Tujuan tidak ditemukan.</div>';
                return;
            }

            destinations.forEach(function (destination) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'destination-result-item';
                button.dataset.destinationId = destination.id;
                button.dataset.destinationLabel = destination.label;
                button.innerHTML =
                    '<strong>' + destination.label + '</strong>' +
                    '<span>ID RajaOngkir: ' + destination.id + '</span>';

                button.addEventListener('click', function () {
                    destinationIdInput.value = destination.id;
                    destinationSearchInput.value = destination.label;
                    selectedDestination.textContent = 'Tujuan dipilih: ' + destination.label;
                    destinationResults.innerHTML = '';
                    shippingStatus.textContent = 'Tujuan siap dihitung';
                });

                destinationResults.appendChild(button);
            });
        }

        if (destinationSearchInput) {
            destinationSearchInput.addEventListener('input', function () {
                resetDestinationSelection('Pilih tujuan dari hasil pencarian.');
            });
        }

        if (destinationSearchButton) {
            destinationSearchButton.addEventListener('click', async function () {
                const search = destinationSearchInput.value.trim();

                if (search.length < 3) {
                    destinationResults.innerHTML = '<div class="destination-empty">Ketik minimal 3 karakter.</div>';
                    return;
                }

                destinationSearchButton.disabled = true;
                destinationResults.innerHTML = '<div class="destination-empty">Mencari tujuan...</div>';

                try {
                    const url = new URL('{{ route('customer.cart.search-destinations') }}', window.location.origin);
                    url.searchParams.set('search', search);

                    const response = await fetch(url.toString(), {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal mencari tujuan.');
                    }

                    renderDestinations(data.data || []);
                } catch (error) {
                    destinationResults.innerHTML = '<div class="destination-empty">' + error.message + '</div>';
                } finally {
                    destinationSearchButton.disabled = false;
                }
            });
        }

        if (estimateButton) {
            estimateButton.addEventListener('click', async function () {
                const destination = destinationIdInput.value;
                const courier = document.getElementById('courier').value;
                const formData = new FormData();

                if (!destination) {
                    shippingStatus.textContent = 'Pilih tujuan dari hasil pencarian dulu.';
                    return;
                }

                selectedKeys().forEach(function (key) {
                    formData.append('selected_items[]', key);
                });

                rows.forEach(function (row) {
                    const key = row.querySelector('.cart-select').value;
                    const qty = row.querySelector('.cart-qty-input').value;
                    formData.append('quantities[' + key + ']', qty);
                });

                formData.append('destination_city_id', destination);
                formData.append('courier', courier);
                formData.append('_token', '{{ csrf_token() }}');

                estimateButton.disabled = true;
                shippingStatus.textContent = 'Menghitung...';

                try {
                    const response = await fetch('{{ route('customer.cart.shipping-estimate') }}', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal menghitung ongkir.');
                    }

                    shippingInput.value = data.cost || 0;
                    shippingServiceInput.value = data.service || '';
                    shippingStatus.textContent = data.service
                        ? data.courier.toUpperCase() + ' ' + data.service + ' - ' + formatRupiah(data.cost)
                        : formatRupiah(data.cost || 0);
                    refreshSummary();
                } catch (error) {
                    shippingInput.value = 0;
                    shippingServiceInput.value = '';
                    shippingStatus.textContent = error.message;
                    refreshSummary();
                } finally {
                    estimateButton.disabled = false;
                }
            });
        }

        refreshSummary();
    });
</script>
@endsection
