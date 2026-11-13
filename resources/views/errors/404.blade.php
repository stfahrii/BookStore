<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found — Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body style="background: #F8FAFC; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px;">
    <div style="max-width: 520px; width: 100%; text-align: center; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 48px 32px; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);">
        <div style="width: 72px; height: 72px; background: #EEF2FF; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #4F46E5;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <div style="font-size: 13px; font-weight: 700; color: #4F46E5; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
            HTTP 404 Not Found
        </div>
        <h1 style="font-size: 26px; font-weight: 800; color: #1E1B4B; margin-bottom: 12px;">Halaman / Data Tidak Ditemukan</h1>
        <p style="font-size: 14px; color: #64748B; line-height: 1.6; margin-bottom: 28px;">
            Halaman atau buku yang Anda cari tidak tersedia, ID tidak valid, atau mungkin telah dipindahkan oleh pengelola.
        </p>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}" class="btn btn-secondary">Beranda Toko</a>
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Dashboard Admin</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Katalog Buku</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk Akun</a>
            @endauth
        </div>
    </div>
</body>
</html>
