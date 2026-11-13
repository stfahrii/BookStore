@extends('layouts.user')
@section('title', 'Katalog Buku & Toko')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('user.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Pencarian & Katalog Buku</span>
</div>

<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h1 class="page-title">Katalog Bookora</h1>
        <p class="page-subtitle" style="margin-bottom: 0;">Beli buku (Add to Cart) atau pinjam secara langsung.</p>
    </div>
    <div style="background: #FFFFFF; border: 1px solid #E2E8F0; padding: 10px 16px; border-radius: 8px; text-align: right; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
        <div style="font-size: 11px; text-transform: uppercase; color: #64748B; font-weight: 600;">Siswa Aktif</div>
        <div style="font-weight: 700; color: #4F46E5; font-size: 14px;">{{ Auth::user()->nama }}</div>
        <div style="font-size: 11.5px; color: #64748B; font-family: monospace;">NIS: {{ Auth::user()->nis }} · {{ Auth::user()->kelas }}</div>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('user.dashboard') }}" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 240px;">
                <input type="text" name="q" class="form-control" placeholder="Cari judul buku, penulis, atau kode..." value="{{ $q }}">
            </div>
            <div style="width: 220px;">
                <select name="kategori" class="form-control">
                    <option value="0">Semua Kategori</option>
                    @foreach($kategori_list as $kat)
                        <option value="{{ $kat->id }}" {{ $kategori_id == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Pencarian Buku
            </button>
            @if($q || $kategori_id)
                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

@if($buku_list->count() > 0)
<div class="book-grid">
    @foreach($buku_list as $b)
    <div class="book-card">
        <div>
            <div style="margin-bottom: 14px; text-align: center;">
                @if(!empty($b->foto) && file_exists(public_path('uploads/' . $b->foto)))
                    <img src="{{ asset('uploads/' . $b->foto) }}" alt="{{ $b->judul }}"
                         style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px; border: 1px solid #E2E8F0;">
                @else
                    <div style="width: 100%; height: 180px; background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #4F46E5; border: 1px dashed #C7D2FE;">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 6px;">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span style="font-size: 12px; font-weight: 600;">Sampul Buku</span>
                    </div>
                @endif
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <span class="book-category">{{ $b->kategori->nama_kategori ?? 'Umum' }}</span>
                @if($b->stok > 0)
                    <span class="badge badge-success" style="font-size: 11px;">
                        Stok: {{ $b->stok }}
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
                <div style="font-size: 12px; color: #64748B;">Lokasi Rak: {{ $b->lokasi_rak }}</div>
            </div>
        </div>

        <div class="book-footer" style="flex-direction: column; gap: 8px; align-items: stretch; margin-top: 14px;">
            @if($b->stok > 0)
                <a href="{{ route('user.cart.add', $b->id) }}" class="btn btn-primary btn-sm btn-full" style="height: 36px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    Beli (Add to Cart)
                </a>
                <a href="{{ route('user.pinjam', $b->id) }}" class="btn btn-secondary btn-sm btn-full"
                   onclick="return confirm('Apakah Anda ingin meminjam buku {{ $b->judul }}?')">
                    Pinjam Buku
                </a>
            @else
                <button class="btn btn-secondary btn-sm btn-full" disabled style="opacity: 0.6; cursor: not-allowed; height: 36px;">
                    Buku Sedang Dipinjam / Stok Habis
                </button>
            @endif
        </div>
    </div>
    @endforeach
</div>

<div class="card" style="margin-top: 24px; padding: 0; overflow: hidden;">
    {{ $buku_list->links('vendor.pagination.custom') }}
</div>
@else
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-title">Buku tidak ditemukan</div>
            <div class="empty-state-desc">Tidak ada koleksi buku yang sesuai dengan pencarian Anda.</div>
        </div>
    </div>
@endif
@endsection
