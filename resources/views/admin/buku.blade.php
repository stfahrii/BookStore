@extends('layouts.admin')
@section('title', 'Kelola Buku & Foto')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Kelola Buku</span>
</div>

<h1 class="page-title">Katalog & Manajemen Buku</h1>
<p class="page-subtitle">Dilengkapi upload foto sampul, proteksi hapus jika di keranjang user, dan stok anti-minus.</p>

<div class="card">
    <div class="card-header">
        <span class="card-title">{{ $edit_data ? 'Edit Data Buku & Sampul' : 'Tambah Buku Baru' }}</span>
        @if($edit_data)
            <a href="{{ route('admin.buku') }}" class="btn btn-secondary btn-sm">Batal Edit</a>
        @endif
    </div>
    <div class="card-body">
        <form method="POST" action="{{ $edit_data ? route('admin.buku.update', $edit_data->id) : route('admin.buku.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Kode Buku <span class="req">*</span></label>
                    <input type="text" name="kode_buku" class="form-control" placeholder="Contoh: BK-006" required
                           value="{{ old('kode_buku', $edit_data->kode_buku ?? ($next_kode ?? '')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori <span class="req">*</span></label>
                    <select name="kategori_id" class="form-control" required>
                        <option value="">— Pilih Kategori —</option>
                        @foreach($kategori_list as $kat)
                            <option value="{{ $kat->id }}" {{ (old('kategori_id', $edit_data->kategori_id ?? '') == $kat->id) ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Terbit <span class="req">*</span></label>
                    <input type="number" name="tahun_terbit" class="form-control" required
                           value="{{ old('tahun_terbit', $edit_data->tahun_terbit ?? date('Y')) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Judul Buku <span class="req">*</span></label>
                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul buku lengkap" required
                       value="{{ old('judul', $edit_data->judul ?? '') }}">
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Penulis / Pengarang <span class="req">*</span></label>
                    <input type="text" name="penulis" class="form-control" required
                           value="{{ old('penulis', $edit_data->penulis ?? '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Penerbit <span class="req">*</span></label>
                    <input type="text" name="penerbit" class="form-control" required
                           value="{{ old('penerbit', $edit_data->penerbit ?? '') }}">
                </div>
            </div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Harga Buku (Rp) <span class="req">*</span></label>
                    <input type="number" name="harga" class="form-control" min="0" step="1000" required
                           value="{{ old('harga', $edit_data->harga ?? 50000) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Stok (Anti-Minus) <span class="req">*</span></label>
                    <input type="number" name="stok" class="form-control" min="0" required
                           value="{{ old('stok', $edit_data->stok ?? 1) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Lokasi Rak Penempatan <span class="req">*</span></label>
                    <input type="text" name="lokasi_rak" class="form-control" required
                           value="{{ old('lokasi_rak', $edit_data->lokasi_rak ?? 'Rak A-1') }}">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Foto Sampul Buku (JPG, PNG, WEBP)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    @if($edit_data && !empty($edit_data->foto))
                        <div style="margin-top: 6px; font-size: 12px; color: #4F46E5;">
                            Foto saat ini: <strong>{{ $edit_data->foto }}</strong>
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" style="height: 42px; padding: 8px;">{{ old('deskripsi', $edit_data->deskripsi ?? '') }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="height: 42px;">
                {{ $edit_data ? 'Simpan Perubahan Buku' : 'Tambahkan ke Katalog' }}
            </button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Koleksi Buku</span>
        <form method="GET" action="{{ route('admin.buku') }}" style="display: flex; gap: 8px;">
            <input type="text" name="q" class="form-control" style="width: 220px; height: 34px; font-size: 13px;"
                   placeholder="Cari judul, kode, penulis..." value="{{ $q }}">
            <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
            @if($q)
                <a href="{{ route('admin.buku') }}" class="btn btn-secondary btn-sm">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        @if($buku_list->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 70px;">Sampul</th>
                    <th>Kode</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok Angka</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($buku_list as $b)
                <tr>
                    <td>
                        @if(!empty($b->foto) && file_exists(public_path('uploads/' . $b->foto)))
                            <img src="{{ asset('uploads/' . $b->foto) }}" alt="Cover" style="width: 48px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #CBD5E1;">
                        @else
                            <div style="width: 48px; height: 60px; background: #EEF2FF; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6366F1; font-size: 11px; font-weight: 700; border: 1px dashed #C7D2FE;">
                                NO IMG
                            </div>
                        @endif
                    </td>
                    <td><span style="font-family: monospace; font-weight: 600;">{{ $b->kode_buku }}</span></td>
                    <td>
                        <strong>{{ $b->judul }}</strong>
                        <div style="font-size: 12px; color: #64748B;">{{ $b->penulis }} · {{ $b->penerbit }}</div>
                    </td>
                    <td><span class="badge badge-primary">{{ $b->kategori->nama_kategori ?? 'Umum' }}</span></td>
                    <td><strong style="color: #4F46E5;">Rp {{ number_format($b->harga, 0, ',', '.') }}</strong></td>
                    <td>
                        <strong style="font-size: 14px; color: {{ $b->stok > 0 ? '#16A34A' : '#DC2626' }};">
                            {{ $b->stok }} Unit
                        </strong>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('buku.detail', $b->id) }}" class="btn btn-secondary btn-sm" target="_blank" title="Lihat Deskripsi Lengkap">Detail</a>
                        <a href="{{ route('admin.buku', ['edit' => $b->id]) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="{{ route('admin.buku.destroy', $b->id) }}" class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus buku {{ $b->judul }} dari katalog?')">
                            Hapus
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $buku_list->links('vendor.pagination.custom') }}
        @else
            <div class="empty-state">
                <div class="empty-state-title">Tidak ada buku ditemukan</div>
                <div class="empty-state-desc">Belum ada data buku yang sesuai dengan pencarian Anda.</div>
            </div>
        @endif
    </div>
</div>
@endsection
