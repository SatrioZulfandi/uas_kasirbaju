<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            font-family: 'Inter', sans-serif; /* Clean minimalist font */
            background-color: #f8f9fa;
            color: #0f172a;
            font-size: 14px; /* Readable small text */
            line-height: 1.4;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .receipt-container {
            width: 320px; /* Standard thermal width approx */
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-radius: 8px; /* Subtle roundness */
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .store-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .store-address {
            font-size: 12px;
            color: #64748b;
        }

        .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 4px;
            color: #475569;
        }

        .divider {
            border-top: 1px dashed #e2e8f0;
            margin: 15px 0;
        }
        
        .divider-solid {
            border-top: 1px solid #0f172a;
            margin: 15px 0;
        }

        .items-table {
            width: 100%;
            font-size: 13px;
        }
        
        .item-row {
            margin-bottom: 8px;
        }
        
        .item-name {
            font-weight: 500;
            display: block;
            margin-bottom: 2px;
        }
        
        .item-details {
            display: flex;
            justify-content: space-between;
            color: #475569;
        }

        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            font-weight: 700;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: #94a3b8;
        }

        .btn-print {
            display: block;
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            text-align: center;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 20px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        @media print {
            body {
                background: none;
                padding: 0;
                display: block;
            }
            .receipt-container {
                box-shadow: none;
                width: 100%; /* Full width for printer */
                padding: 0;
                border-radius: 0;
            }
            .btn-print, .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <div style="margin-bottom: 10px;">
                <img src="{{ asset('images/logo.png') }}" alt="Satz Apparel" style="max-width: 150px; height: auto; filter: grayscale(100%);">
            </div>
            <div class="store-address">Jl. Fashion No. 123, Jakarta</div>
            <div class="store-address">Telp: (021) 1234-5678</div>
        </div>

        <div class="divider"></div>

        <div class="meta-info">
            <span>No. Order</span>
            <span style="font-weight: 600;">#{{ $transaction->order_id ?? str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="meta-info">
            <span>Tanggal</span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="meta-info">
            <span>Kasir</span>
            <span>{{ $transaction->user->name }}</span>
        </div>

        <div class="divider"></div>

        <div class="items-list">
            @php $grandTotal = 0; @endphp
            @foreach($transactions as $item)
            @php $grandTotal += $item->total_price; @endphp
            <div class="item-row">
                <span class="item-name">{{ $item->product->name }} {{ $item->size ? '('.$item->size.')' : '' }}</span>
                <div class="item-details">
                    <span>{{ $item->quantity }} x {{ number_format($item->product->price, 0, ',', '.') }}</span>
                    <span>{{ number_format($item->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="divider-solid"></div>

        <div class="total-section">
            <span>TOTAL</span>
            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
        </div>

        <div class="footer">
            Terima kasih atas kunjungan Anda!<br>
            Barang yang sudah dibeli tidak dapat dikembalikan
        </div>

        <button onclick="window.print()" class="btn-print">Cetak Struk</button>
    </div>
</body>
</html>
