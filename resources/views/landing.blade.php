<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookora — Toko Buku & Pustaka Terpercaya</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        .landing-hero {
            background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%);
            color: #FFFFFF;
            padding: 56px 24px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .landing-hero-container {
            max-width: 820px;
            margin: 0 auto;
        }
        .landing-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            letter-spacing: 0.3px;
        }
        .landing-hero h1 {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.25;
            margin: 0 0 16px 0;
            letter-spacing: -0.5px;
        }
        .landing-hero p {
            font-size: 16px;
            color: #E0E7FF;
            line-height: 1.6;
            margin: 0 0 32px 0;
        }
        .landing-search-box {
            display: flex;
            gap: 10px;
            background: #FFFFFF;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            max-width: 640px;
            margin: 0 auto;
        }
        .landing-search-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 10px 16px;
            font-size: 15px;
            color: #1E293B;
            background: transparent;
        }
        .landing-search-btn {
            background: #4F46E5;
            color: #FFFFFF;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s ease;
        }
        .landing-search-btn:hover {
            background: #4338CA;
        }
        .landing-features {
            max-width: 1140px;
            margin: -24px auto 36px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            position: relative;
            z-index: 2;
        }
        .feature-card {
            background: #FFFFFF;
            padding: 22px;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .feature-icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #EEF2FF;
            color: #4F46E5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .feature-card h4 {
            margin: 0 0 4px 0;
            font-size: 15px;
            font-weight: 700;
            color: #0F172A;
        }
        .feature-card p {
            margin: 0;
            font-size: 13px;
            color: #64748B;
            line-height: 1.5;
        }
        .landing-content {
            max-width: 1140px;
            margin: 0 auto;
            padding: 20px 20px 60px 20px;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .section-title {
            font-size: 24px;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 6px 0;
        }
        .section-subtitle {
            font-size: 14px;
            color: #64748B;
            margin: 0;
        }
        .category-pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .category-pill {
            padding: 8px 16px;
            background: #FFFFFF;
            border: 1px solid var(--border);
            border-radius: 99px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .category-pill:hover,
        .category-pill.active {
            background: #4F46E5;
            color: #FFFFFF;
            border-color: #4F46E5;
        }
        .public-footer {
            background: #0F172A;
            color: #94A3B8;
            padding: 40px 20px 24px 20px;
            font-size: 13px;
            border-top: 1px solid #1E293B;
        }
        .public-footer-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        @media (max-width: 768px) {
            .landing-features {
                grid-template-columns: 1fr;
                margin-top: 20px;
            }
            .landing-hero h1 {
                font-size: 28px;
            }
            .landing-search-box {
                flex-direction: column;
            }
        }
    </style>
</head>
<body style="background: #F8FAFC;">

    <!-- TOPBAR / NAVBAR PUBLIK -->
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
            @auth
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm" style="display: flex; align-items: center; gap: 6px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        </svg>
                        Dashboard Admin
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary btn-sm" style="color: #fff; border: 1px solid rgba(255,255,255,0.25); background: rgba(255,255,255,0.1);">
                        Katalog Saya
                    </a>
                    <a href="{{ route('user.cart') }}" class="btn btn-secondary btn-sm" style="color: #fff; border: 1px solid rgba(255,255,255,0.25); background: rgba(255,255,255,0.1); display: flex; align-items: center; gap: 6px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        Keranjang
                    </a>
                @endif
                <a href="{{ route('logout') }}" class="topbar-logout-btn">
                    Keluar
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="color: #fff; border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.1);">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="background: #4F46E5; border-color: #4F46E5;">
                    Daftar Akun
                </a>
            @endauth
        </div>
    </header>

    <!-- HERO SECTION (LANDING PAGE) -->
    <section class="landing-hero">
        <div class="landing-hero-container">
            <div class="landing-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 14 14"></polyline>
                </svg>
                Toko Buku & Layanan Pustaka Mandiri
            </div>
            <h1>Temukan Buku Berkualitas untuk Masa Depan Anda</h1>
            <p>Jelajahi beragam kategori buku terbaik dengan sistem pemesanan mudah dan metode pembayaran langsung di tempat (Payment at Delivery).</p>

            <!-- FITUR SEARCH PRODUK -->
            <form method="GET" action="{{ route('home') }}" class="landing-search-box">
                <input type="text" name="q" class="landing-search-input"
                       placeholder="Cari judul buku, nama penulis, atau kode buku..."
                       value="{{ $q ?? '' }}">
                @if(!empty($kategori_id))
                    <input type="hidden" name="kategori" value="{{ $kategori_id }}">
                @endif
                <button type="submit" class="landing-search-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    Cari Buku
                </button>
            </form>
        </div>
    </section>

    <!-- 3 KEUNGGULAN TOKO BUKU -->
    <div class="landing-features">
        <div class="feature-card">
            <div class="feature-icon-wrapper">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div>
                <h4>Koleksi Pilihan</h4>
                <p>Ribuan judul buku akademik, pengembangan diri, dan literatur.</p>
            </div>
        </div>
        <div class="feature-card">
            <div class="feature-icon-wrapper">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13"></rect>
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                </svg>
            </div>
            <div>
                <h4>Payment at Delivery</h4>
                <p>Bayar dengan aman saat buku pesanan tiba di alamat tujuan.</p>
            </div>
        </div>
        <div class="feature-card">
            <div class="feature-icon-wrapper">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <div>
                <h4>100% Produk Asli</h4>
                <p>Seluruh buku langsung diterbitkan dari penerbit resmi.</p>
            </div>
        </div>
    </div>

    <!-- PANDUAN & ALUR TATA CARA PEMINJAMAN BUKU SESUAI REVISI GURU -->
    <section style="background: #FFFFFF; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding: 50px 24px; margin-bottom: 36px;">
        <div style="max-width: 1140px; margin: 0 auto;">
            <div style="text-align: center; max-width: 720px; margin: 0 auto 36px auto;">
                <div style="display: inline-flex; align-items: center; gap: 6px; background: #EEF2FF; color: #4F46E5; padding: 4px 14px; border-radius: 99px; font-size: 12px; font-weight: 700; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Panduan Pustaka Digital
                </div>
                <h2 style="font-size: 26px; font-weight: 800; color: #1E1B4B; margin-bottom: 8px;">Tata Cara & Alur Peminjaman Buku</h2>
                <p style="font-size: 14px; color: #64748B; line-height: 1.6; margin: 0;">
                    Layanan peminjaman buku di Bookora dibuat mudah, transparan, dan terintegrasi otomatis dengan sistem inventaris perpustakaan.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <!-- Langkah 1 -->
                <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 12px; padding: 22px; position: relative;">
                    <div style="width: 34px; height: 34px; background: #4F46E5; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; margin-bottom: 14px;">
                        1
                    </div>
                    <h3 style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Daftar / Masuk Akun</h3>
                    <p style="font-size: 12.5px; color: #64748B; line-height: 1.5; margin: 0;">
                        Masuk ke akun Anda atau klik <strong>Daftar Akun</strong> dengan nomor identitas untuk mulai meminjam.
                    </p>
                </div>

                <!-- Langkah 2 -->
                <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 12px; padding: 22px; position: relative;">
                    <div style="width: 34px; height: 34px; background: #4F46E5; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; margin-bottom: 14px;">
                        2
                    </div>
                    <h3 style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Pilih Buku di Katalog</h3>
                    <p style="font-size: 12.5px; color: #64748B; line-height: 1.5; margin: 0;">
                        Cari buku yang diinginkan, periksa info lokasi rak fisik, dan klik tombol <strong>Pinjam</strong>.
                    </p>
                </div>

                <!-- Langkah 3 -->
                <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 12px; padding: 22px; position: relative;">
                    <div style="width: 34px; height: 34px; background: #4F46E5; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; margin-bottom: 14px;">
                        3
                    </div>
                    <h3 style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Verifikasi Petugas</h3>
                    <p style="font-size: 12.5px; color: #64748B; line-height: 1.5; margin: 0;">
                        Petugas perpustakaan mengonfirmasi pinjaman dan stok buku fisik berkurang otomatis secara real-time.
                    </p>
                </div>

                <!-- Langkah 4 -->
                <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 12px; padding: 22px; position: relative;">
                    <div style="width: 34px; height: 34px; background: #16A34A; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; margin-bottom: 14px;">
                        4
                    </div>
                    <h3 style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Batas Waktu 7 Hari</h3>
                    <p style="font-size: 12.5px; color: #64748B; line-height: 1.5; margin: 0;">
                        Buku dipinjam dengan batas tempo <strong>7 hari kalender</strong> sejak tanggal pengajuan pinjaman disetujui.
                    </p>
                </div>

                <!-- Langkah 5 -->
                <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 12px; padding: 22px; position: relative;">
                    <div style="width: 34px; height: 34px; background: #D97706; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; margin-bottom: 14px;">
                        5
                    </div>
                    <h3 style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 6px;">Kembalikan Tepat Waktu</h3>
                    <p style="font-size: 12.5px; color: #64748B; line-height: 1.5; margin: 0;">
                        Kembalikan buku sebelum tempo lewat tombol <em>Kembalikan</em> untuk mencegah <strong>suspensi akun</strong>.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN PRODUCT / HOME KATALOG SECTION (READ PRODUK) -->
    <main class="landing-content" id="katalog">
        <div class="section-header">
            <div>
                <h2 class="section-title">Katalog Produk Buku</h2>
                <p class="section-subtitle">
                    @if(!empty($q))
                        Hasil pencarian untuk kata kunci: <strong>"{{ $q }}"</strong>
                    @else
                        Menampilkan seluruh koleksi buku yang tersedia di Bookora
                    @endif
                </p>
            </div>
            @if(!empty($q) || !empty($kategori_id))
                <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                    Reset Filter
                </a>
            @endif
        </div>

        <!-- FILTER KATEGORI -->
        <div class="category-pills">
            <a href="{{ route('home', array_filter(['q' => $q])) }}"
               class="category-pill {{ empty($kategori_id) ? 'active' : '' }}">
                Semua Kategori ({{ $total_buku }})
            </a>
            @foreach($kategori_list as $kat)
                <a href="{{ route('home', array_filter(['kategori' => $kat->id, 'q' => $q])) }}"
                   class="category-pill {{ ($kategori_id == $kat->id) ? 'active' : '' }}">
                    {{ $kat->nama_kategori }} ({{ $kat->bukus_count }})
                </a>
            @endforeach
        </div>

        <!-- GRID PRODUK BUKU (READ PRODUK) -->
        @if($buku_list->count() > 0)
            <div class="book-grid">
                @foreach($buku_list as $b)
                <div class="book-card">
                    <div>
                        <div style="margin-bottom: 14px; text-align: center;">
                            <a href="{{ route('buku.detail', $b->id) }}" style="display: block; text-decoration: none;">
                                @if(!empty($b->foto) && file_exists(public_path('uploads/' . $b->foto)))
                                    <img src="{{ asset('uploads/' . $b->foto) }}" alt="{{ $b->judul }}"
                                         style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px; border: 1px solid #E2E8F0; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                @else
                                    <div style="width: 100%; height: 180px; background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #4F46E5; border: 1px dashed #C7D2FE;">
                                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 6px;">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        <span style="font-size: 12px; font-weight: 600;">Sampul Buku</span>
                                    </div>
                                @endif
                            </a>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <span class="book-category">{{ $b->kategori->nama_kategori ?? 'Umum' }}</span>
                            @if($b->stok > 0)
                                <span class="badge badge-success" style="font-size: 11px;">
                                    Stok: {{ $b->stok }}
                                </span>
                            @else
                                <span class="badge badge-danger" style="font-size: 11px;">
                                    Habis
                                </span>
                            @endif
                        </div>

                        <h3 class="book-title">
                            <a href="{{ route('buku.detail', $b->id) }}" style="color: inherit; text-decoration: none;" title="Lihat detail buku">
                                {{ $b->judul }}
                            </a>
                        </h3>

                        <div style="margin-bottom: 8px;">
                            <a href="{{ route('buku.detail', $b->id) }}" style="font-size: 12px; color: #4F46E5; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                Baca Sinopsis &raquo;
                            </a>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <span style="font-size: 18px; font-weight: 800; color: #4F46E5;">
                                Rp {{ number_format($b->harga, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="book-meta">
                            <div>Penulis: <strong>{{ $b->penulis }}</strong></div>
                            <div>Penerbit: {{ $b->penerbit }} ({{ $b->tahun_terbit }})</div>
                        </div>
                    </div>

                    <!-- ACTION BUTTON -->
                    <div class="book-footer" style="flex-direction: column; gap: 8px; align-items: stretch; margin-top: 14px;">
                        @auth
                            @if(Auth::user()->role === 'anggota')
                                @if($b->stok > 0)
                                    <a href="{{ route('user.cart.add', $b->id) }}" class="btn btn-primary btn-sm btn-full" style="height: 38px;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="21" r="1"></circle>
                                            <circle cx="20" cy="21" r="1"></circle>
                                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                        </svg>
                                        Beli / Masukkan Keranjang
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm btn-full" disabled style="opacity: 0.6; cursor: not-allowed; height: 38px;">
                                        Stok Habis
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('admin.buku') }}" class="btn btn-secondary btn-sm btn-full" style="height: 38px;">
                                    Kelola di Admin
                                </a>
                            @endif
                        @else
                            @if($b->stok > 0)
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm btn-full" style="height: 38px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    Login untuk Membeli
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm btn-full" disabled style="opacity: 0.6; cursor: not-allowed; height: 38px;">
                                    Stok Habis
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>

            <div class="card" style="margin-top: 24px; padding: 0; overflow: hidden;">
                {{ $buku_list->links('vendor.pagination.custom') }}
            </div>
        @else
            <div class="empty-state" style="background: #FFFFFF; border-radius: 12px; border: 1px solid var(--border); padding: 48px 24px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="color: #94A3B8; margin-bottom: 12px;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <h3>Buku Tidak Ditemukan</h3>
                <p>Tidak ada buku yang sesuai dengan pencarian atau filter kategori yang dipilih.</p>
                <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" style="margin-top: 14px;">
                    Lihat Semua Buku
                </a>
            </div>
        @endif
    </main>

    <!-- FOOTER PUBLIK -->
    <footer class="public-footer">
        <div class="public-footer-inner">
            <div>
                <strong style="color: #FFFFFF; font-size: 15px;">Bookora</strong> — Aplikasi Toko Buku & Pustaka Mandiri
                <div style="font-size: 12px; margin-top: 4px; color: #64748B;">
                    Uji Sertifikasi Kompetensi (USK) Junior Web Developer · BNSP
                </div>
            </div>
            <div style="display: flex; gap: 16px;">
                <a href="{{ route('login') }}" style="color: #94A3B8; text-decoration: none;">Masuk Akun</a>
                <a href="{{ route('register') }}" style="color: #94A3B8; text-decoration: none;">Pendaftaran</a>
            </div>
        </div>
    </footer>

</body>
</html>
