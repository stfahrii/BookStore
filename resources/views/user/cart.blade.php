@extends('layouts.user')
@section('title', 'Keranjang Belanja')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('user.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Keranjang Belanja (Add to Cart)</span>
</div>

<h1 class="page-title">Keranjang Belanja Buku</h1>
<p class="page-subtitle">Periksa buku pilihan Anda, atur jumlah pesanan, dan lanjutkan ke pembayaran pengiriman.</p>

@if(!empty($has_stock_issue))
    <div class="alert alert-error" style="margin-bottom: 20px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <div>
            <strong>Perhatian: Stok Berubah!</strong><br>
            Ada buku di keranjang Anda yang keburu habis atau stoknya berkurang karena telah dibeli oleh pengguna lain. Silakan hapus item yang habis atau sesuaikan kuantitas untuk dapat melanjutkan ke pembayaran.
        </div>
    </div>
@endif

@if(!empty($cart_items))
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: flex-start;">
    <div class="card" style="margin-bottom: 0;">
        <div class="card-header">
            <span class="card-title">Daftar Buku di Keranjang ({{ count($cart_items) }} Judul)</span>
            <a href="{{ route('user.cart.clear') }}" class="btn btn-secondary btn-sm" onclick="return confirm('Kosongkan keranjang belanja?')">
                Kosongkan Keranjang
            </a>
        </div>
        <form method="POST" action="{{ route('user.cart.update') }}">
            @csrf
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sampul</th>
                            <th>Judul Buku</th>
                            <th>Harga Satuan</th>
                            <th style="width: 100px;">Jumlah</th>
                            <th>Subtotal</th>
                            <th style="text-align: right; width: 60px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart_items as $item)
                        <tr style="{{ $item['is_out_of_stock'] ? 'background: #FEF2F2;' : ($item['is_insufficient'] ? 'background: #FFFBEB;' : '') }}">
                            <td style="width: 60px;">
                                @if(!empty($item['buku']->foto) && file_exists(public_path('uploads/' . $item['buku']->foto)))
                                    <img src="{{ asset('uploads/' . $item['buku']->foto) }}" alt="Cover" style="width: 44px; height: 56px; object-fit: cover; border-radius: 4px; border: 1px solid #CBD5E1; {{ $item['is_out_of_stock'] ? 'filter: grayscale(100%); opacity: 0.6;' : '' }}">
                                @else
                                    <div style="width: 44px; height: 56px; background: #EEF2FF; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #6366F1; font-size: 10px; font-weight: 700;">NO IMG</div>
                                @endif
                            </td>
                            <td>
                                <strong style="{{ $item['is_out_of_stock'] ? 'color: #991B1B;' : '' }}">{{ $item['buku']->judul }}</strong>
                                <div style="font-size: 12px; color: #64748B;">Penulis: {{ $item['buku']->penulis }}</div>
                                
                                @if($item['is_out_of_stock'])
                                    <div style="margin-top: 4px;">
                                        <span class="badge badge-danger" style="font-size: 11px;">
                                            TIDAK TERSEDIA (Habis Dibeli)
                                        </span>
                                    </div>
                                @elseif($item['is_insufficient'])
                                    <div style="margin-top: 4px;">
                                        <span class="badge badge-warning" style="font-size: 11px;">
                                            Stok Tersisa {{ $item['buku']->stok }} Unit
                                        </span>
                                    </div>
                                @else
                                    <div style="font-size: 11px; color: #16A34A; margin-top: 2px;">
                                        Stok tersedia: {{ $item['buku']->stok }}
                                    </div>
                                @endif
                            </td>
                            <td>Rp {{ number_format($item['buku']->harga, 0, ',', '.') }}</td>
                            <td>
                                @if($item['is_out_of_stock'])
                                    <input type="text" class="form-control" value="0" disabled
                                           style="height: 34px; padding: 4px 8px; font-size: 13px; background: #F1F5F9; color: #94A3B8; text-align: center; cursor: not-allowed;" title="Buku ini sudah tidak tersedia">
                                @else
                                    <input type="number" name="qty[{{ $item['buku']->id }}]" class="form-control"
                                           value="{{ min($item['qty'], $item['buku']->stok) }}" min="1" max="{{ $item['buku']->stok }}"
                                           style="height: 34px; padding: 4px 8px; font-size: 13px;">
                                @endif
                            </td>
                            <td>
                                @if($item['is_out_of_stock'])
                                    <span style="color: #94A3B8; font-size: 13px; text-decoration: line-through;">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </span>
                                @else
                                    <strong style="color: #4F46E5;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('user.cart.remove', $item['buku']->id) }}" class="btn btn-danger btn-sm" style="padding: 0 8px;" title="Hapus dari keranjang">
                                    &times;
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 16px 20px; border-top: 1px solid #E2E8F0; display: flex; justify-content: space-between;">
                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary btn-sm">Tambah Buku Lain</a>
                <button type="submit" class="btn btn-secondary btn-sm">Perbarui Jumlah</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Ringkasan Pesanan</span>
        </div>
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                <span style="color: #64748B;">Total Item:</span>
                <strong>{{ array_sum(session('cart', [])) }} Buku</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 14px;">
                <span style="color: #64748B;">Biaya Pengiriman:</span>
                <strong style="color: #16A34A;">GRATIS</strong>
            </div>
            <div style="border-top: 1px dashed #CBD5E1; padding-top: 14px; margin-top: 14px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <span style="font-size: 15px; font-weight: 600; color: #0F172A;">Total Pembayaran:</span>
                <strong style="font-size: 20px; font-weight: 800; color: #4F46E5;">
                    Rp {{ number_format($total_harga, 0, ',', '.') }}
                </strong>
            </div>

            @if(!empty($has_stock_issue))
                <button type="button" class="btn btn-secondary btn-full" disabled style="height: 44px; opacity: 0.6; cursor: not-allowed; background: #E2E8F0; color: #64748B; font-weight: 700;">
                    Checkout Dinonaktifkan (Ada Barang Habis)
                </button>
                <div style="font-size: 11.5px; color: #E11D48; text-align: center; margin-top: 8px;">
                    Hapus barang bertanda <strong>TIDAK TERSEDIA</strong> terlebih dahulu.
                </div>
            @else
                <a href="{{ route('user.checkout') }}" class="btn btn-primary btn-full" style="height: 44px; font-size: 14px;">
                    Lanjut ke Pembayaran (Checkout)
                </a>
            @endif

            <div style="text-align: center; margin-top: 12px; font-size: 12px; color: #64748B;">
                Metode: <strong>Payment at Delivery</strong> (Bayar saat buku dikirim)
            </div>
        </div>
    </div>
</div>
@else
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-title">Keranjang belanja Anda masih kosong</div>
            <div class="empty-state-desc">Pilih buku favorit Anda di halaman katalog dan klik tombol <strong>"Beli (Add to Cart)"</strong>.</div>
            <div style="margin-top: 16px;">
                <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Lihat Katalog Buku</a>
            </div>
        </div>
    </div>
@endif
@endsection
