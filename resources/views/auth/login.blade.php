<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Bookora</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-modal">
            <div style="margin-bottom: 16px;">
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 6px; color: var(--text-muted); font-size: 13px; text-decoration: none; font-weight: 500; transition: color 0.15s ease;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="auth-icon-badge">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>

            <div class="auth-header">
                <h1 class="auth-title">Bookora</h1>
                <p class="auth-subtitle">Masuk untuk mulai berbelanja buku</p>
            </div>

            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email Akun <span class="req">*</span></label>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="nama@email.com" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                           title="Harap masukkan email lengkap dengan domain (contoh: nama@gmail.com)"
                           required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password <span class="req">*</span></label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Masukkan password Anda" required>
                </div>

                <button type="submit" class="btn btn-primary btn-full" style="margin-top: 10px; height: 42px;">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="auth-footer" style="display: flex; flex-direction: column; gap: 8px;">
                <div>
                    Belum memiliki akun? <a href="{{ route('register') }}">Daftar Akun</a>
                </div>
                <div style="border-top: 1px solid var(--border); padding-top: 8px; margin-top: 2px;">
                    <a href="{{ route('home') }}" style="color: #4F46E5; text-decoration: none; font-size: 12.5px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Lihat Katalog Produk di Landing Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
