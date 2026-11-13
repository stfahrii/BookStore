<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku — {{ $buku->judul }} — Bookora</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    @php
        $user = Auth::user();
        $is_admin = $user && $user->role === 'admin';
        $is_siswa = $user && ($user->role === 'anggota' || $user->role === 'user');
        $total_cart = 0;
        if ($is_siswa) {
            $total_cart = \Illuminate\Support\Facades\DB::table('cart_items')->where('user_id', $user->id)->sum('jumlah');
        }
    @endphp

    <header class="topbar">
        <div class="topbar-left">
            <a href="{{ route('home') }}" class="topbar-brand">
                <div class="topbar-logo-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <span class="topbar-title">Bookora</span>
            </a>
        </div>
        <div class="topbar-meta">
            @if($is_siswa)
                <a href="{{ route('user.cart') }}" class="btn btn-secondary btn-sm" style="display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25);">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>Keranjang</span>
                    @if($total_cart > 0)
                        <span style="background: #E11D48; color: #fff; font-size: 11px; font-weight: 700; padding: 1px 6px; border-radius: 99px;">
                            {{ $total_cart }}
                        </span>
                    @endif
                </a>
            @endif

            @auth
                <div class="topbar-user">
                    <div class="topbar-user-badge">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>{{ Auth::user()->nama }} ({{ ucfirst(Auth::user()->role) }})</span>
                    </div>
                    <a href="{{ route('logout') }}" class="topbar-logout-btn">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Keluar</span>
                    </a>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="color: #fff; border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.1);">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="background: #4F46E5;">Daftar Akun</a>
            @endauth
        </div>
    </header>

    <div class="{{ $user ? 'app-layout' : '' }}" id="{{ $user ? 'app-layout' : '' }}">
        @if($user)
            <aside class="sidebar" id="app-sidebar">
                <nav class="sidebar-nav">
                    @if($is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
                        <a href="{{ route('admin.buku') }}" class="nav-item active">Kelola Buku</a>
                        <a href="{{ route('admin.kategori') }}" class="nav-item">Kelola Kategori</a>
                        <a href="{{ route('admin.pesanan') }}" class="nav-item">Kelola Pesanan</a>
                        <a href="{{ route('admin.peminjaman') }}" class="nav-item">Kelola Peminjaman</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="nav-item active">Katalog Buku</a>
                        <a href="{{ route('user.cart') }}" class="nav-item">Keranjang Belanja</a>
                        <a href="{{ route('user.pesanan_saya') }}" class="nav-item">Pesanan Saya</a>
                        <a href="{{ route('user.riwayat') }}" class="nav-item">Peminjaman Pustaka</a>
                        <a href="{{ route('user.profil') }}" class="nav-item">Profil Saya</a>
                    @endif
                </nav>
            </aside>
        @endif

        <main class="{{ $user ? 'main-content' : 'container' }}" style="{{ !$user ? 'max-width: 1080px; margin: 32px auto; padding: 0 20px;' : '' }}">
            <!-- BREADCRUMB -->
            <div class="breadcrumb">
                @if($is_siswa)
                    <a href="{{ route('user.dashboard') }}">Bookora</a>
                    <span class="breadcrumb-sep">/</span>
                    <a href="{{ route('user.dashboard') }}">Katalog Buku</a>
                @elseif($is_admin)
                    <a href="{{ route('admin.dashboard') }}">Bookora</a>
                    <span class="breadcrumb-sep">/</span>
                    <a href="{{ route('admin.buku') }}">Kelola Buku</a>
                @else
                    <a href="{{ route('home') }}">Bookora</a>
                    <span class="breadcrumb-sep">/</span>
                    <a href="{{ route('home') }}">Katalog Buku</a>
                @endif
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">{{ $buku->judul }}</span>
            </div>

            @include('layouts.alerts')

            <div class="detail-buku-card">
                <!-- KOLOM KIRI: SAMPUL & AKSI BELI/PINJAM -->
                <div>
                    <div class="detail-cover-box">
                        @if(!empty($buku->foto) && file_exists(public_path('uploads/' . $buku->foto)))
                            <img src="{{ asset('uploads/' . $buku->foto) }}" alt="{{ $buku->judul }}">
                        @else
                            <div class="detail-cover-placeholder">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <span>NO COVER IMAGE</span>
                            </div>
                        @endif
                    </div>

                    <div style="margin-top: 18px; text-align: center;">
                        <div style="font-size: 12px; color: #64748B; margin-bottom: 4px;">Harga Resmi</div>
                        <div style="font-size: 24px; font-weight: 800; color: #16A34A; margin-bottom: 12px;">
                            Rp {{ number_format($buku->harga, 0, ',', '.') }}
                        </div>

                        @if($buku->stok > 0)
                            <span class="badge badge-success" style="padding: 6px 14px; font-size: 13px;">
                                {{ $buku->stok }} Eksemplar Tersedia
                            </span>
                        @else
                            <span class="badge badge-danger" style="padding: 6px 14px; font-size: 13px;">
                                Stok Buku Habis
                            </span>
                        @endif
                    </div>

                    <!-- TOMBOL AKSI -->
                    <div style="margin-top: 24px; display: flex; flex-direction: column; gap: 10px;">
                        @if($is_siswa)
                            <a href="{{ route('user.cart.add', $buku->id) }}" class="btn btn-primary btn-full" style="height: 42px; display: flex; align-items: center; justify-content: center; gap: 8px; {{ $buku->stok <= 0 ? 'pointer-events: none; opacity: 0.5;' : '' }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="9" cy="21" r="1"></circle>
                                    <circle cx="20" cy="21" r="1"></circle>
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                </svg>
                                Beli (Tambah ke Keranjang)
                            </a>

                            <a href="{{ route('user.pinjam', $buku->id) }}" 
                               class="btn btn-secondary btn-full" 
                               style="height: 42px; border: 1px solid #CBD5E1; display: flex; align-items: center; justify-content: center; gap: 8px; {{ $buku->stok <= 0 ? 'pointer-events: none; opacity: 0.5;' : '' }}"
                               onclick="return confirm('Ajukan peminjaman buku {{ addslashes($buku->judul) }} selama 7 hari?')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                Pinjam Buku Pustaka
                            </a>

                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary btn-full" style="height: 42px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                &larr; Kembali ke Katalog Buku
                            </a>
                        @elseif($is_admin)
                            <a href="{{ route('admin.buku', ['edit' => $buku->id]) }}" class="btn btn-primary btn-full" style="height: 42px; display: flex; align-items: center; justify-content: center;">
                                Edit Data Buku Ini
                            </a>
                            <a href="{{ route('admin.buku') }}" class="btn btn-secondary btn-full" style="height: 42px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                &larr; Kembali ke Kelola Buku
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-full" style="height: 42px; display: flex; align-items: center; justify-content: center;">
                                Masuk Akun untuk Beli / Pinjam
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-secondary btn-full" style="height: 42px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                &larr; Kembali ke Beranda
                            </a>
                        @endif
                    </div>
                </div>

                <!-- KOLOM KANAN: SPESIFIKASI & DESKRIPSI LENGKAP -->
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <span class="badge badge-primary" style="font-size: 13px; padding: 4px 12px;">
                            {{ $buku->kategori->nama_kategori ?? 'Koleksi Umum' }}
                        </span>
                        <span style="font-family: monospace; font-size: 12px; color: #4F46E5; font-weight: 700; background: #EEF2FF; padding: 3px 8px; border-radius: 4px;">
                            {{ $buku->kode_buku }}
                        </span>
                    </div>

                    <h1 style="font-size: 26px; font-weight: 800; color: #1E1B4B; line-height: 1.3; margin-bottom: 12px;">
                        {{ $buku->judul }}
                    </h1>

                    <div style="font-size: 14px; color: #64748B; margin-bottom: 20px;">
                        Karya Penulis: <strong style="color: #0F172A;">{{ $buku->penulis }}</strong>
                    </div>

                    <!-- SPESIFIKASI LENGKAP -->
                    <div class="detail-spec-grid">
                        <div class="detail-spec-item">
                            <span class="detail-spec-label">Kode Inventaris</span>
                            <span class="detail-spec-value">{{ $buku->kode_buku }}</span>
                        </div>
                        <div class="detail-spec-item">
                            <span class="detail-spec-label">Penerbit</span>
                            <span class="detail-spec-value">{{ $buku->penerbit }}</span>
                        </div>
                        <div class="detail-spec-item">
                            <span class="detail-spec-label">Tahun Terbit</span>
                            <span class="detail-spec-value">{{ $buku->tahun_terbit }}</span>
                        </div>
                        <div class="detail-spec-item">
                            <span class="detail-spec-label">Lokasi Rak</span>
                            <span class="detail-spec-value">{{ $buku->lokasi_rak }}</span>
                        </div>
                        <div class="detail-spec-item">
                            <span class="detail-spec-label">Kategori</span>
                            <span class="detail-spec-value">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</span>
                        </div>
                        <div class="detail-spec-item">
                            <span class="detail-spec-label">Status Stok</span>
                            <span class="detail-spec-value" style="color: {{ $buku->stok > 0 ? '#16A34A' : '#DC2626' }};">
                                {{ $buku->stok > 0 ? $buku->stok . ' Unit' : 'Habis' }}
                            </span>
                        </div>
                    </div>

                    <!-- DESKRIPSI & SINOPSIS -->
                    <div class="detail-desc-box">
                        <h3 class="detail-desc-title">Sinopsis & Deskripsi Buku</h3>
                        <div class="detail-desc-content">
                            {!! !empty($buku->deskripsi) ? nl2br(e($buku->deskripsi)) : 'Belum ada deskripsi lengkap untuk buku ini. Buku ini merupakan koleksi referensi bacaan terpercaya di Bookora.' !!}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
