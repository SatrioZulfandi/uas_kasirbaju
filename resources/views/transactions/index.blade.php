@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<h1>Riwayat Transaksi</h1>

<div class="card" style="margin-bottom: 1.5rem;">
    <form action="{{ route('transactions.index') }}" method="GET" style="display: flex; gap: 1rem; align-items: flex-end;">
        <div style="flex: 1;">
            <label style="display: block; margin-bottom: 0.5rem; color: #64748b; font-size: 0.9rem;">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border); border-radius: 0.5rem;">
        </div>
        <div style="flex: 1;">
            <label style="display: block; margin-bottom: 0.5rem; color: #64748b; font-size: 0.9rem;">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="form-control" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border); border-radius: 0.5rem;">
        </div>
        <button type="submit" class="btn btn-primary" style="height: 42px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            Filter
        </button>
        @if(request('start_date') || request('end_date'))
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary" style="height: 42px; display: inline-flex; align-items: center; text-decoration: none;">Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                @if(auth()->user()->isAdmin())
                    <th>Kasir</th>
                @endif
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <!-- ... columns ... -->
                     <td>
                        <div style="font-weight: 500;">{{ $transaction->created_at->format('d/m/Y') }}</div>
                        <div style="font-size: 0.8rem; color: #64748b;">{{ $transaction->created_at->format('H:i') }}</div>
                        @if($transaction->order_id)
                            <div style="font-size: 0.75rem; color: var(--primary);">#{{ $transaction->order_id }}</div>
                        @endif
                    </td>
                    @if(auth()->user()->isAdmin())
                        <td>{{ $transaction->user->name }}</td>
                    @endif
                    <td>
                        <div style="max-width: 250px;">
                            {{ Str::limit($transaction->product_summary, 50) }}
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $transaction->items_count }} Item</span>
                    </td>
                    <td style="font-weight: 600; color: var(--success);">
                        Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                    </td>
                    <td>
                        <button onclick="openReceipt('{{ route('transactions.receipt', $transaction->id) }}')" 
                           class="btn btn-primary btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                            Cetak Struk
                        </button>
                    </td>
                </tr>
            @empty
                <!-- ... -->
            @endforelse
        </tbody>
    </table>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 1rem; border-radius: 0.75rem; width: 360px; max-width: 90%; position: relative;">
        <button onclick="closeReceipt()" style="position: absolute; top: 10px; right: 10px; border: none; background: none; font-size: 1.5rem; cursor: pointer; color: #64748b;">&times;</button>
        <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Cetak Struk</h3>
        <iframe id="receiptFrame" style="width: 100%; height: 500px; border: 1px solid #e2e8f0; border-radius: 0.5rem;"></iframe>
    </div>
</div>

<script>
    function openReceipt(url) {
        const modal = document.getElementById('receiptModal');
        const frame = document.getElementById('receiptFrame');
        
        frame.src = url;
        modal.style.display = 'flex';
        
        // Optional: Auto print when loaded
        // frame.onload = function() {
        //     frame.contentWindow.print();
        // };
    }

    function closeReceipt() {
        document.getElementById('receiptModal').style.display = 'none';
        document.getElementById('receiptFrame').src = 'about:blank';
    }

    // Close on click outside
    document.getElementById('receiptModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReceipt();
        }
    });
</script>
@endsection
