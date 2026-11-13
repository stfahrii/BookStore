<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pengguna — Bookora</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-modal auth-modal-wide">
            <div style="margin-bottom: 16px;">
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 6px; color: var(--text-muted); font-size: 13px; text-decoration: none; font-weight: 500;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="auth-icon-badge">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>

            <div class="auth-header">
                <h1 class="auth-title">Pendaftaran Akun Baru</h1>
                <p class="auth-subtitle">Daftarkan akun Anda untuk mulai berbelanja di Bookora</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div>
                        @foreach($errors->all() as $err)
                            <div>• {{ $err }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required
                               value="{{ old('nama') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Identitas (NIS / NIK) <span class="req">*</span></label>
                        <input type="text" name="nis" class="form-control" placeholder="Masukkan NIS atau NIK" required
                               value="{{ old('nis') }}">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Alamat Email <span class="req">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="nama@email.com"
                               pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                               title="Harap masukkan email lengkap dengan domain (contoh: nama@gmail.com)"
                               required value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon / WhatsApp <span class="req">*</span></label>
                        <input type="text" name="telepon" class="form-control" placeholder="Nomor telepon aktif" required
                               value="{{ old('telepon') }}">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Password <span class="req">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password <span class="req">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-full" style="height: 44px; margin-top: 10px; font-size: 14px;">
                    Daftar Sekarang
                </button>
            </form>

            <div class="auth-footer" style="display: flex; flex-direction: column; gap: 8px;">
                <div>
                    Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a>
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
