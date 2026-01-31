<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir Toko Pakaian')</title>
    <style>
        :root {
            /* Minimalist Color Palette */
            --primary: #2563eb;       /* Professional Blue */
            --primary-dark: #1d4ed8;
            --secondary: #64748b;     /* Slate Gray */
            --success: #16a34a;       /* Green */
            --danger: #dc2626;        /* Red */
            --warning: #ca8a04;       /* Dark Yellow */
            --background: #f8f9fa;    /* Light Gray Background */
            --surface: #ffffff;       /* White Surface */
            --text-main: #0f172a;     /* Dark Slate */
            --text-secondary: #475569;
            --border: #e2e8f0;        /* Light Border */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Layout Structure */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background-color: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 50;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .brand {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: -0.025em;
        }
        
        .brand svg {
            color: var(--primary);
            width: 24px;
            height: 24px;
        }

        .sidebar-nav {
            padding: 1.5rem 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        
        .nav-link:hover {
            transform: translateX(4px);
        }

        .nav-link svg {
            width: 20px;
            height: 20px;
            stroke-width: 2px;
        }

        .nav-link:hover, .nav-link.active {
            background-color: #eff6ff;
            color: var(--primary);
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: #eff6ff;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
            width: calc(100% - 260px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Utility & Components (Kept from previous) */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            transition: all 0.15s;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            gap: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-primary { background-color: var(--primary); color: white; }
        .btn-primary:hover { background-color: var(--primary-dark); }
        .btn-danger { background-color: var(--danger); color: white; }
        .btn-warning { background-color: var(--warning); color: white; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }

        .alert { padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem; }
        .alert-success { background-color: #dcfce7; color: #166534; }
        .alert-error { background-color: #fee2e2; color: #991b1b; }

        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { text-align: left; padding: 0.75rem 1rem; background: #f8fafc; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--secondary); border-bottom: 1px solid var(--border); }
        td { padding: 1rem; border-bottom: 1px solid var(--border); font-size: 0.9rem; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        
        /* Table Row Hover */
        tbody tr:hover {
            background-color: #f8fafc;
            transition: background-color 0.2s;
        }

        .form-control { width: 100%; padding: 0.625rem; border: 1px solid var(--border); border-radius: 0.375rem; font-size: 0.95rem; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.375rem; font-weight: 500; font-size: 0.9rem; }
        
        .badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 500; display: inline-block; }
        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .badge-secondary { background-color: #f1f5f9; color: #64748b; }
        
        .text-muted { color: var(--secondary); }
        .font-bold { font-weight: 600; }
        .mb-4 { margin-bottom: 1rem; }
        .mt-4 { margin-top: 1rem; }

        @media print {
            .sidebar, .no-print { display: none !important; }
            .main-content { margin: 0; padding: 0; width: 100%; }
        }
    </style>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    @auth
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Brand -->
            <div style="padding: 1.25rem 1rem; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border);">
                <img src="{{ asset('images/logo.png') }}" alt="Satz Apparel" style="max-width: 120px; height: auto; display: block;">
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->isAdmin())
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                    Produk
                </a>
                @endif

                <a href="{{ route('transactions.create') }}" class="nav-link {{ request()->routeIs('transactions.create') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Transaksi Baru
                </a>

                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    Riwayat
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div style="flex: 1; overflow: hidden;">
                        <div style="font-weight: 600; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ auth()->user()->name }}
                        </div>
                        <div style="font-size: 0.8rem; color: var(--secondary);">
                            {{ ucfirst(auth()->user()->role) }}
                        </div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="width: 100%; background: #fee2e2; color: #991b1b; border: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @else
        <!-- Login Layout (Centered) -->
        <main style="display: flex; justify-content: center; align-items: center; min-height: 100vh; width: 100%; background: var(--background);">
            @yield('content')
        </main>
    @endauth
    @yield('scripts')
</body>
</html>
