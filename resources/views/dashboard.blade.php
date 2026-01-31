@extends('layouts.app')

@section('title', 'Dashboard - Kasir Toko Pakaian Satrio')

@section('content')
<style>
    .dashboard-header {
        margin-bottom: 2rem;
    }
    
    .welcome-text {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .date-text {
        color: var(--text-secondary);
        font-size: 0.95rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: var(--primary);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-content h3 {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.2;
    }

    .stat-sub {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
    }

    /* Quick Actions */
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 1rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.25rem;
    }

    .action-card {
        background: var(--surface);
        border: 1px solid var(--border);
        padding: 1.5rem;
        border-radius: 1rem;
        text-decoration: none;
        color: var(--text-main);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.2s;
        gap: 1rem;
    }

    .action-card:hover {
        border-color: var(--primary);
        background: #f8fafc;
        transform: translateY(-2px);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #eff6ff;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .action-card:hover .action-icon {
        background: var(--primary);
        color: white;
    }

    .action-title {
        font-weight: 600;
        font-size: 1rem;
    }

    .action-desc {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
</style>

<div class="dashboard-header">
    <h1 class="welcome-text">Selamat Datang, {{ auth()->user()->name }}!</h1>
    <p class="date-text">
        {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }} &bull; 
        <span class="badge badge-secondary">{{ ucfirst(auth()->user()->role) }}</span>
    </p>
</div>

<!-- Stats Section -->
<div class="stats-grid">
    <!-- Total Sales -->
    <div class="stat-card">
        <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Total Penjualan</h3>
            <div class="stat-value">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="stat-sub">Semua waktu</div>
        </div>
    </div>

    <!-- Product Bestseller -->
    <div class="stat-card">
        <div class="stat-icon" style="background: #fff7ed; color: #ea580c;">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="7"></circle>
                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
            </svg>
        </div>
        <div class="stat-content">
            <h3>Produk Terlaris</h3>
            @if($mostPurchased)
                <div class="stat-value" style="font-size: 1.25rem;">{{ Str::limit($mostPurchased->product->name, 20) }}</div>
                <div class="stat-sub">
                    <b>{{ $mostPurchased->total_quantity }}</b> terjual dari {{ $mostPurchased->purchase_count }} transaksi
                </div>
            @else
                <div class="stat-value" style="font-size: 1.25rem;">-</div>
                <div class="stat-sub">Belum ada data</div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<h2 class="section-title">Menu Cepat</h2>
<div class="actions-grid">
    <a href="{{ route('transactions.create') }}" class="action-card">
        <div class="action-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </div>
        <div>
            <div class="action-title">Transaksi Baru</div>
            <div class="action-desc">Buat pesanan pelanggan</div>
        </div>
    </a>

    <a href="{{ route('transactions.index') }}" class="action-card">
        <div class="action-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <div>
            <div class="action-title">Riwayat</div>
            <div class="action-desc">Lihat data penjualan</div>
        </div>
    </a>

    @if(auth()->user()->isAdmin())
    <a href="{{ route('products.index') }}" class="action-card">
        <div class="action-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
        </div>
        <div>
            <div class="action-title">Produk</div>
            <div class="action-desc">Kelola stok & harga</div>
        </div>
    </a>
    @endif
</div>
@endsection
