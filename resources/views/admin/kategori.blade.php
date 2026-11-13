@extends('layouts.admin')
@section('title', 'Kategori Buku')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Kategori Buku</span>
</div>

<h1 class="page-title">Kelola Kategori Buku</h1>
<p class="page-subtitle">Kategori dilindungi oleh validasi: tidak dapat dihapus jika masih menampung buku dan stok fisik.</p>

<div class="card">
    <div class="card-header">
        <span class="card-title">{{ $edit_data ? 'Edit Kategori Buku' : 'Tambah Kategori Baru' }}</span>
        @if($edit_data)
            <a href="{{ route('admin.kategori') }}" class="btn btn-secondary btn-sm">Batal Edit</a>
        @endif
    </div>
    <div class="card-body">
        <form method="POST" action="{{ $edit_data ? route('admin.kategori.update', $edit_data->id) : route('admin.kategori.store') }}" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
            @csrf
            <div class="form-group" style="flex: 1; min-width: 280px; margin-bottom: 0;">
                <label class="form-label">Nama Kategori Buku <span class="req">*</span></label>
                <input type="text" name="nama_kategori" class="form-control" required
                       placeholder="Contoh: Novel & Fiksi, Komputer & TI..."
                       value="{{ old('nama_kategori', $edit_data->nama_kategori ?? '') }}">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 42px;">
                {{ $edit_data ? 'Simpan Perubahan' : 'Tambahkan Kategori' }}
            </button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Kategori Buku</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Judul Buku</th>
                    <th>Total Stok Fisik</th>
                    <th>Status Keamanan</th>
                    <th style="text-align: right; width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori_list as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $row->nama_kategori }}</strong></td>
                    <td><span class="badge badge-primary">{{ $row->bukus_count }} Judul Buku</span></td>
                    <td><strong style="color: #0F172A;">{{ $row->bukus_sum_stok ?? 0 }} Eksemplar</strong></td>
                    <td>
                        @if($row->bukus_count > 0)
                            <span class="badge badge-warning">Berisi Buku (Terkunci)</span>
                        @else
                            <span class="badge badge-neutral">Kosong (Bisa Dihapus)</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.kategori', ['edit' => $row->id]) }}" class="btn btn-secondary btn-sm">Edit</a>
                        @if($row->bukus_count > 0)
                            <button class="btn btn-danger btn-sm" disabled style="opacity: 0.5; cursor: not-allowed;" title="Tidak bisa dihapus karena masih ada buku">
                                Hapus
                            </button>
                        @else
                            <a href="{{ route('admin.kategori.destroy', $row->id) }}" class="btn btn-danger btn-sm"
                               onclick="return confirm('Hapus kategori {{ $row->nama_kategori }}?')">
                                Hapus
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
