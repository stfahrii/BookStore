@extends('layouts.admin')
@section('title', 'Dashboard Administrator')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Dashboard Admin</span>
</div>

<h1 class="page-title">Dashboard Administrator</h1>
<p class="page-subtitle">Selamat datang kembali, <strong>{{ Auth::user()->nama }}</strong>. Memonitor penjualan toko buku dan sirkulasi pustaka.</p>

<div class="stats-grid">
    <div class="stat-card stat-indigo">
        <div class="stat-info">
            <span class="stat-label">Total Judul Buku</span>
            <span class="stat-number">{{ $total_buku }}</span>
        </div>
        <div class="stat-icon-wrapper">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
        </div>
    </div>

    <div class="stat-card stat-emerald">
        <div class="stat-info">
            <span class="stat-label">Total Omset Penjualan</span>
            <span class="stat-number" style="font-size: 20px;">Rp {{ number_format($total_omset, 0, ',', '.') }}</span>
        </div>
        <div class="stat-icon-wrapper">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
    </div>

    <div class="stat-card stat-amber">
        <div class="stat-info">
            <span class="stat-label">User / Anggota Terdaftar</span>
            <span class="stat-number">{{ $total_anggota }}</span>
        </div>
        <div class="stat-icon-wrapper">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
            </svg>
        </div>
    </div>

    <div class="stat-card stat-rose">
        <div class="stat-info">
            <span class="stat-label">Total Pesanan Masuk</span>
            <span class="stat-number">{{ $total_order }}</span>
        </div>
        <div class="stat-icon-wrapper">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title">Pesanan Buku Terbaru dari User</span>
        <a href="{{ route('admin.pesanan') }}" class="btn btn-secondary btn-sm">Kelola Semua Pesanan</a>
    </div>
    <div class="table-responsive">
        @if($recent_orders->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Kode Order</th>
                    <th>User Pembeli</th>
                    <th>Penerima</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_orders as $ro)
                <tr>
                    <td><strong style="font-family: monospace; color: #4F46E5;">{{ $ro->kode_order }}</strong></td>
                    <td>{{ $ro->user->nama ?? '-' }}</td>
                    <td>{{ $ro->nama_penerima }}</td>
                    <td><strong style="color: #16A34A;">Rp {{ number_format($ro->total_bayar, 0, ',', '.') }}</strong></td>
                    <td>
                        @if($ro->status_order === 'menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif($ro->status_order === 'diproses')
                            <span class="badge badge-primary">Diproses</span>
                        @elseif($ro->status_order === 'dikirim')
                            <span class="badge badge-warning">Dikirim</span>
                        @elseif($ro->status_order === 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.pesanan') }}" class="btn btn-secondary btn-sm">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">
                <div class="empty-state-title">Belum ada pesanan buku</div>
                <div class="empty-state-desc">Pesanan yang dibuat oleh user akan otomatis muncul di sini.</div>
            </div>
        @endif
    </div>
</div>
@endsection
