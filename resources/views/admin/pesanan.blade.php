@extends('layouts.admin')
@section('title', 'List Pesanan User')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">List Pesanan Buku</span>
</div>

<h1 class="page-title">List Pesanan Buku (Orders)</h1>
<p class="page-subtitle">Monitoring pesanan buku dari user yang berbeda-beda dan rincian transaksi lengkap.</p>

<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Semua Pesanan Masuk</span>
        <div style="display: flex; gap: 6px;">
            <a href="{{ route('admin.pesanan', ['status' => 'semua']) }}" class="btn btn-sm {{ $filter === 'semua' ? 'btn-primary' : 'btn-secondary' }}">Semua</a>
            <a href="{{ route('admin.pesanan', ['status' => 'menunggu']) }}" class="btn btn-sm {{ $filter === 'menunggu' ? 'btn-primary' : 'btn-secondary' }}">Menunggu</a>
            <a href="{{ route('admin.pesanan', ['status' => 'diproses']) }}" class="btn btn-sm {{ $filter === 'diproses' ? 'btn-primary' : 'btn-secondary' }}">Diproses</a>
            <a href="{{ route('admin.pesanan', ['status' => 'dikirim']) }}" class="btn btn-sm {{ $filter === 'dikirim' ? 'btn-primary' : 'btn-secondary' }}">Dikirim</a>
            <a href="{{ route('admin.pesanan', ['status' => 'selesai']) }}" class="btn btn-sm {{ $filter === 'selesai' ? 'btn-primary' : 'btn-secondary' }}">Selesai</a>
        </div>
    </div>
    <div class="table-responsive">
        @if($order_list->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Kode Order</th>
                    <th>User Siswa</th>
                    <th>Penerima & Alamat</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Aksi / Info Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order_list as $row)
                <tr>
                    <td>
                        <strong style="font-family: monospace; color: #4F46E5;">{{ $row->kode_order }}</strong>
                        <div style="font-size: 11px; color: #64748B;">{{ date('d M Y H:i', strtotime($row->created_at)) }}</div>
                    </td>
                    <td>
                        <strong>{{ $row->user->nama ?? '-' }}</strong>
                        <div style="font-size: 12px; color: #64748B;">NIS: {{ $row->user->nis ?? '-' }} · {{ $row->user->kelas ?? '-' }}</div>
                    </td>
                    <td>
                        <div><strong>{{ $row->nama_penerima }}</strong> ({{ $row->telepon }})</div>
                        <div style="font-size: 12px; color: #64748B; max-width: 250px;">{{ $row->alamat_pengiriman }}</div>
                        <div style="margin-top: 6px; font-size: 12px; background: #F8FAFC; padding: 4px 8px; border-radius: 4px; border: 1px solid #E2E8F0;">
                            @foreach($row->items as $it)
                                <div>• {{ $it->buku->judul ?? '-' }} ({{ $it->jumlah }}x)</div>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <strong style="color: #16A34A; font-size: 14px;">Rp {{ number_format($row->total_bayar, 0, ',', '.') }}</strong>
                        <div style="font-size: 11px; color: #64748B;">{{ $row->metode_pembayaran }}</div>
                    </td>
                    <td>
                        @if($row->status_order === 'menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif($row->status_order === 'diproses')
                            <span class="badge badge-primary">Diproses</span>
                        @elseif($row->status_order === 'dikirim')
                            <span class="badge badge-warning">Sedang Dikirim</span>
                        @elseif($row->status_order === 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            @if($row->status_order === 'menunggu')
                                <form method="POST" action="{{ route('admin.pesanan.status', $row->id) }}">
                                    @csrf
                                    <input type="hidden" name="status_order" value="diproses">
                                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; font-size: 11.5px; padding: 5px 8px; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        Proses Pesanan
                                    </button>
                                </form>
                            @elseif($row->status_order === 'diproses')
                                <form method="POST" action="{{ route('admin.pesanan.status', $row->id) }}">
                                    @csrf
                                    <input type="hidden" name="status_order" value="dikirim">
                                    <button type="submit" class="btn btn-sm" style="width: 100%; font-size: 11.5px; padding: 5px 8px; background: #0284C7; color: #fff; border: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                        Kirim Paket
                                    </button>
                                </form>
                            @elseif($row->status_order === 'dikirim')
                                <form method="POST" action="{{ route('admin.pesanan.status', $row->id) }}">
                                    @csrf
                                    <input type="hidden" name="status_order" value="selesai">
                                    <button type="submit" class="btn btn-sm" style="width: 100%; font-size: 11.5px; padding: 5px 8px; background: #16A34A; color: #fff; border: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Konfirmasi Selesai
                                    </button>
                                </form>
                            @elseif($row->status_order === 'selesai')
                                <div style="font-size: 11px; color: #16A34A; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 5px 6px; background: #DCFCE7; border-radius: 6px; border: 1px solid #BBF7D0;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Pesanan Selesai (Terkunci)
                                </div>
                            @endif

                            <button type="button" class="btn btn-secondary btn-sm" style="font-size: 11px; height: 28px;"
                                    onclick="alert('INFO PESANAN #{{ $row->kode_order }}\nPenerima: {{ addslashes($row->nama_penerima) }}\nTelepon: {{ $row->telepon }}\nAlamat: {{ addslashes($row->alamat_pengiriman) }}\nTotal: Rp {{ number_format($row->total_bayar, 0, ',', '.') }}')">
                                Info / Detail
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="empty-state">
                <div class="empty-state-title">Belum ada pesanan buku</div>
                <div class="empty-state-desc">Pesanan yang dibuat oleh user melalui cart checkout akan tercatat di sini.</div>
            </div>
        @endif
    </div>
</div>
@endsection
