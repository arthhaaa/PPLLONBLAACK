<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $transaction['kode_transaksi'] }} - Long Black</title>
    <link rel="icon" type="image/png" href="{{ asset('img/long-black-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --receipt-ink: #2f221f;
            --receipt-muted: #77645c;
            --receipt-line: #eadbd0;
            --receipt-brand: #4b2e2b;
            --receipt-accent: #b35c0c;
            --receipt-paper: #fffaf4;
        }

        body {
            margin: 0;
            background: #f3ece4;
            color: var(--receipt-ink);
            font-family: Arial, sans-serif;
        }

        .receipt-toolbar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            width: min(920px, calc(100% - 32px));
            margin: 24px auto 16px;
        }

        .receipt-toolbar a,
        .receipt-toolbar button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 8px;
            border: 0;
            font-weight: 800;
            text-decoration: none;
        }

        .receipt-toolbar a {
            background: #ffffff;
            color: var(--receipt-brand);
            border: 1px solid var(--receipt-line);
        }

        .receipt-toolbar button {
            background: var(--receipt-brand);
            color: #ffffff;
        }

        .receipt-sheet {
            width: min(920px, calc(100% - 32px));
            margin: 0 auto 36px;
            padding: 34px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 24px 70px rgba(75, 46, 43, 0.14);
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            padding-bottom: 24px;
            border-bottom: 2px solid var(--receipt-line);
        }

        .receipt-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .receipt-brand img {
            width: 62px;
            height: 62px;
            object-fit: contain;
        }

        .receipt-brand h1,
        .receipt-code strong {
            color: var(--receipt-brand);
            font-weight: 900;
        }

        .receipt-brand h1 {
            margin: 0;
            font-size: 24px;
        }

        .receipt-brand p,
        .receipt-code span,
        .receipt-info span,
        .receipt-note span {
            margin: 0;
            color: var(--receipt-muted);
            font-size: 13px;
            font-weight: 700;
        }

        .receipt-code {
            text-align: right;
        }

        .receipt-code strong {
            display: block;
            font-size: 21px;
            margin-top: 5px;
        }

        .receipt-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin: 24px 0;
        }

        .receipt-info,
        .receipt-note {
            padding: 18px;
            border: 1px solid var(--receipt-line);
            border-radius: 8px;
            background: var(--receipt-paper);
        }

        .receipt-info strong,
        .receipt-note p {
            display: block;
            margin-top: 6px;
            color: var(--receipt-ink);
            font-weight: 800;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 8px;
        }

        .receipt-table th {
            background: var(--receipt-brand);
            color: #ffffff;
            font-size: 12px;
            text-transform: uppercase;
        }

        .receipt-table th,
        .receipt-table td {
            padding: 13px 12px;
            border-bottom: 1px solid var(--receipt-line);
            vertical-align: top;
        }

        .receipt-table td {
            color: var(--receipt-ink);
        }

        .receipt-table small {
            color: var(--receipt-muted);
            font-weight: 700;
        }

        .receipt-total {
            width: min(360px, 100%);
            margin-left: auto;
            margin-top: 22px;
        }

        .receipt-total div {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding: 10px 0;
            border-bottom: 1px solid var(--receipt-line);
        }

        .receipt-total div:last-child {
            margin-top: 8px;
            padding: 14px 16px;
            border: 0;
            border-radius: 8px;
            background: var(--receipt-brand);
            color: #ffffff;
            font-size: 18px;
            font-weight: 900;
        }

        .receipt-footer {
            margin-top: 28px;
            padding-top: 18px;
            border-top: 1px solid var(--receipt-line);
            color: var(--receipt-muted);
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 640px) {
            .receipt-toolbar,
            .receipt-header,
            .receipt-grid {
                flex-direction: column;
                grid-template-columns: 1fr;
            }

            .receipt-code {
                text-align: left;
            }

            .receipt-sheet {
                padding: 22px;
            }

            .receipt-table {
                font-size: 13px;
            }
        }

        @media print {
            body {
                background: #ffffff;
            }

            .receipt-toolbar {
                display: none;
            }

            .receipt-sheet {
                width: 100%;
                margin: 0;
                padding: 0;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-toolbar">
        <a href="{{ $backUrl }}"><i class="fa fa-arrow-left"></i> {{ $backLabel }}</a>
        <button type="button" onclick="window.print()"><i class="fa fa-print"></i> Cetak Struk</button>
    </div>

    <main class="receipt-sheet">
        <header class="receipt-header">
            <div class="receipt-brand">
                <img src="{{ asset('img/long-black-logo.png') }}" alt="Long Black">
                <div>
                    <h1>LONG BLACK</h1>
                    <p>Invoice Struk Pembelian</p>
                </div>
            </div>
            <div class="receipt-code">
                <span>Kode Transaksi</span>
                <strong>{{ $transaction['kode_transaksi'] }}</strong>
                <p class="mb-0">{{ $transaction['created_at'] ? $transaction['created_at']->format('d M Y H:i') : '-' }}</p>
            </div>
        </header>

        <section class="receipt-grid">
            <div class="receipt-info">
                <span>Customer</span>
                <strong>{{ $customerName ?? '-' }}</strong>
                @if($customerEmail)
                    <p class="mb-0">{{ $customerEmail }}</p>
                @endif
                @if($customerPhone)
                    <p class="mb-0">{{ $customerPhone }}</p>
                @endif
            </div>
            <div class="receipt-info">
                <span>Pembayaran & Status</span>
                <strong>{{ $transaction['metode_pembayaran'] ?? '-' }}</strong>
                <p class="mb-0">{{ ucwords(str_replace('_', ' ', $transaction['status_transaksi'])) }}</p>
                @if($transaction['midtrans_transaction_id'] ?? null)
                    <p class="mb-0">Midtrans: {{ $transaction['midtrans_transaction_id'] }}</p>
                @endif
            </div>
            <div class="receipt-info">
                <span>Pengiriman</span>
                <strong>{{ strtoupper($transaction['kurir'] ?? '-') }} {{ $transaction['layanan_kurir'] ?? '' }}</strong>
                <p class="mb-0">{{ $transaction['alamat_pengiriman'] ?? '-' }}</p>
            </div>
            <div class="receipt-note">
                <span>Catatan</span>
                <p class="mb-0">{{ $transaction['catatan'] ?: '-' }}</p>
            </div>
        </section>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Bentuk</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->produk?->nama_produk ?? $item->nama_produk }}</strong>
                            <small class="d-block">PRD-{{ str_pad($item->id_produk ?? 0, 4, '0', STR_PAD_LEFT) }}</small>
                        </td>
                        <td>{{ ucfirst($item->bentuk_produk ?? 'biji') }}</td>
                        <td class="text-center">{{ $item->total_produk }}</td>
                        <td class="text-end">Rp {{ number_format((float) $item->total_harga_produk, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <section class="receipt-total">
            <div>
                <span>Subtotal Produk</span>
                <strong>Rp {{ number_format((float) $transaction['subtotal_produk'], 0, ',', '.') }}</strong>
            </div>
            <div>
                <span>Ongkir</span>
                <strong>Rp {{ number_format((float) $transaction['ongkir'], 0, ',', '.') }}</strong>
            </div>
            <div>
                <span>Total Bayar</span>
                <strong>Rp {{ number_format((float) $transaction['total_harga'], 0, ',', '.') }}</strong>
            </div>
        </section>

        <footer class="receipt-footer">
            Terima kasih sudah berbelanja di Long Black.
        </footer>
    </main>
</body>
</html>
