@extends('layouts.user')
@section('title', 'Pesanan Saya')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('user.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Pesanan Saya</span>
</div>

<h1 class="page-title">Daftar Pesanan Buku Saya</h1>
<p class="page-subtitle">Pantau status konfirmasi pesanan, pengiriman buku, dan riwayat belanja Anda.</p>

<div class="card">
    <div class="card-header">
        <span class="card-title">Riwayat Transaksi Pembelian Buku</span>
    </div>
    <div class="table-responsive">
        @if($orders->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Kode Order</th>
                    <th>Buku yang Dibeli</th>
                    <th>Alamat Kirim</th>
                    <th>Total Tagihan</th>
                    <th>Status Pesanan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $ord)
                <tr>
                    <td>
                        <strong style="font-family: monospace; color: #4F46E5; font-size: 14px;">{{ $ord->kode_order }}</strong>
                        <div style="font-size: 11px; color: #64748B;">{{ date('d M Y H:i', strtotime($ord->created_at)) }}</div>
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            @foreach($ord->items as $it)
                                <div style="font-size: 13px;">
                                    • <strong>{{ $it->buku->judul ?? '-' }}</strong> 
                                    <span style="color: #64748B;">({{ $it->jumlah }}x @ Rp {{ number_format($it->harga, 0, ',', '.') }})</span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 13px; font-weight: 500;">{{ $ord->nama_penerima }} ({{ $ord->telepon }})</div>
                        <div style="font-size: 12px; color: #64748B; max-width: 220px;">{{ $ord->alamat_pengiriman }}</div>
                    </td>
                    <td>
                        <strong style="color: #16A34A; font-size: 14px;">Rp {{ number_format($ord->total_bayar, 0, ',', '.') }}</strong>
                        <div style="font-size: 11px; color: #64748B;">{{ $ord->metode_pembayaran }}</div>
                    </td>
                    <td>
                        @if($ord->status_order === 'menunggu')
                            <span class="badge badge-warning">Menunggu Konfirmasi</span>
                        @elseif($ord->status_order === 'diproses')
                            <span class="badge badge-primary">Sedang Diproses</span>
                        @elseif($ord->status_order === 'dikirim')
                            <span class="badge badge-warning">Sedang Dikirim</span>
                        @elseif($ord->status_order === 'selesai')
                            <span class="badge badge-success">Selesai Diterima</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">
                <div class="empty-state-title">Belum ada pesanan buku</div>
                <div class="empty-state-desc">Anda belum pernah melakukan pemesanan buku di Bookora.</div>
                <div style="margin-top: 16px;">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-sm">Mulai Belanja Buku</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
