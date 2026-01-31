<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir Toko Pakaian</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #111827; /* Near black for premium feel */
            --primary-hover: #000000;
            --text-main: #1f2937;
            --text-sub: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0,0,0,0.03);
        }

        .brand-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: var(--primary);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-main);
            letter-spacing: -0.025em;
        }

        .brand-subtitle {
            font-size: 0.875rem;
            color: var(--text-sub);
            margin-top: 0.25rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: #f9fafb;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 2px rgba(17, 24, 39, 0.05);
        }

        .btn-submit {
            width: 100%;
            padding: 0.875rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }

        .remember-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .remember-checkbox {
            width: 1rem; 
            height: 1rem;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-label {
            font-size: 0.875rem;
            color: var(--text-sub);
            cursor: pointer;
        }

        .demo-box {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .demo-text {
            font-size: 0.75rem;
            color: var(--text-sub);
            line-height: 1.5;
        }
        
        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            list-style-position: inside;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-section">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <img src="{{ asset('images/logo.png') }}" alt="Satz Apparel" style="height: 60px; object-fit: contain;">
            </div>
            <h1 class="brand-title">Login</h1>
            <p class="brand-subtitle">Silakan masuk untuk melanjutkan</p>
        </div>

        @if($errors->any())
            <ul class="alert-error">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <div class="form-group remember-group">
                <input type="checkbox" id="remember" name="remember" class="remember-checkbox">
                <label for="remember" class="remember-label">Ingat saya</label>
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

    </div>
</body>
</html>
