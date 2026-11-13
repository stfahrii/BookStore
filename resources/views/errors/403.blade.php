<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden — Akses Ditolak</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body style="background: #F8FAFC; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px;">
    <div style="max-width: 520px; width: 100%; text-align: center; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 48px 32px; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);">
        <div style="width: 72px; height: 72px; background: #FEE2E2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #DC2626;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>
        <div style="font-size: 13px; font-weight: 700; color: #DC2626; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
            HTTP 403 Forbidden
        </div>
        <h1 style="font-size: 26px; font-weight: 800; color: #1E1B4B; margin-bottom: 12px;">Akses Ditolak / Dibatasi</h1>
        <p style="font-size: 14px; color: #64748B; line-height: 1.6; margin-bottom: 28px;">
            Maaf, akun Anda tidak memiliki hak akses (<strong>Otorisasi</strong>) untuk membuka halaman atau direktori ini. Halaman ini hanya diperuntukkan bagi Administrator.
        </p>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Dashboard Admin</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        </svg>
                        Kembali ke Katalog Saya
                    </a>
                @endif
            @else
                <a href="{{ route('home') }}" class="btn btn-secondary">Beranda Toko</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk Akun</a>
            @endauth
        </div>
    </div>
</body>
</html>
