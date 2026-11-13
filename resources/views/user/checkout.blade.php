@extends('layouts.user')
@section('title', 'Checkout Pembayaran')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('user.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('user.cart') }}">Keranjang</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Payment at Delivery (Checkout)</span>
</div>

<h1 class="page-title">Pembayaran & Konfirmasi Pesanan</h1>
<p class="page-subtitle">Sistem pembayaran sebelum buku dikirimkan ke alamat Anda (Payment at Delivery).</p>

<div style="display: grid; grid-template-columns: 3fr 2fr; gap: 24px; align-items: flex-start;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Informasi Pengiriman & Penerima</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('user.checkout.process') }}">
                @csrf
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Penerima <span class="req">*</span></label>
                        <input type="text" name="nama_penerima" class="form-control" required
                               value="{{ old('nama_penerima', $user->nama) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp / HP <span class="req">*</span></label>
                        <input type="text" name="telepon" class="form-control" required
                               placeholder="Contoh: 08123456789"
                               value="{{ old('telepon', $user->telepon) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap Pengiriman <span class="req">*</span></label>
                    <textarea name="alamat_pengiriman" class="form-control" style="height: 90px; padding: 10px;" required
                              placeholder="Nama jalan, nomor rumah, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos...">{{ old('alamat_pengiriman') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Metode Pembayaran <span class="req">*</span></label>
                    <p style="font-size: 12px; color: #64748B; margin-top: -4px; margin-bottom: 10px;">
                        Pilih metode pembayaran yang Anda inginkan (pembayaran sebelum buku dikirimkan):
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <!-- Opsi 1: QRIS -->
                        <label id="label-qris" style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; border: 1.5px solid #4F46E5; border-radius: 8px; background: #EEF2FF; cursor: pointer; transition: all 0.15s ease;">
                            <input type="radio" name="metode_pembayaran" value="QRIS (Gopay, OVO, Dana, Bank)" checked style="margin-top: 3px;" onchange="updatePaymentView('qris')">
                            <div>
                                <strong style="font-size: 13.5px; color: #1E1B4B;">QRIS (Scan Semua E-Wallet & Bank)</strong>
                                <div style="font-size: 12px; color: #475569;">BCA Mobile, GoPay, OVO, DANA, ShopeePay, Livin Mandiri</div>
                            </div>
                        </label>

                        <!-- Opsi 2: Transfer Bank -->
                        <label id="label-bank" style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; border: 1.5px solid var(--border); border-radius: 8px; background: #FFFFFF; cursor: pointer; transition: all 0.15s ease;">
                            <input type="radio" name="metode_pembayaran" value="Transfer Bank / Virtual Account" style="margin-top: 3px;" onchange="updatePaymentView('bank')">
                            <div>
                                <strong style="font-size: 13.5px; color: #1E1B4B;">Transfer Bank (Virtual Account)</strong>
                                <div style="font-size: 12px; color: #475569;">BCA: 8720-1234-5678 | Mandiri: 137-00-1234-567 a.n Bookora</div>
                            </div>
                        </label>

                        <!-- Opsi 3: Payment at Delivery -->
                        <label id="label-cod" style="display: flex; align-items: flex-start; gap: 10px; padding: 12px; border: 1.5px solid var(--border); border-radius: 8px; background: #FFFFFF; cursor: pointer; transition: all 0.15s ease;">
                            <input type="radio" name="metode_pembayaran" value="Payment at Delivery (Bayar Tunai Sebelum Dikirim)" style="margin-top: 3px;" onchange="updatePaymentView('cod')">
                            <div>
                                <strong style="font-size: 13.5px; color: #1E1B4B;">Payment at Delivery (Tunai / Petugas Toko)</strong>
                                <div style="font-size: 12px; color: #475569;">Pembayaran tunai kepada petugas Bookora sebelum buku dikirim</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Petunjuk Tambahan Pembayaran Dinamis -->
                <div id="payment-info-box" style="background: #F8FAFC; border: 1px dashed #C7D2FE; padding: 14px; border-radius: 8px; margin-bottom: 14px;">
                    <div style="font-weight: 700; font-size: 12px; color: #4338CA; margin-bottom: 6px; letter-spacing: 0.3px;">
                        PETUNJUK PEMBAYARAN QRIS
                    </div>
                    <div style="font-size: 12.5px; color: #475569; line-height: 1.5;">
                        Setelah mengklik tombol konfirmasi pesanan di bawah, sistem akan menerbitkan nomor pesanan dan Anda dapat menyelesaikan pembayaran sebesar <strong>Rp {{ number_format($total_bayar, 0, ',', '.') }}</strong>.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan Tambahan untuk Petugas</label>
                    <input type="text" name="catatan" class="form-control" placeholder="Opsional, misalnya patokan rumah" value="{{ old('catatan') }}">
                </div>

                <button type="submit" class="btn btn-primary btn-full" style="height: 44px; margin-top: 10px; font-size: 14px;">
                    Konfirmasi & Proses Pesanan
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Ringkasan Buku yang Dipesan</span>
        </div>
        <div class="card-body" style="padding: 16px 20px;">
            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                @foreach($cart_items as $item)
                <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 12px; border-bottom: 1px solid #F1F5F9;">
                    <div>
                        <div style="font-weight: 600; color: #0F172A;">{{ $item['buku']->judul }}</div>
                        <div style="font-size: 12px; color: #64748B;">{{ $item['qty'] }}x @ Rp {{ number_format($item['buku']->harga, 0, ',', '.') }}</div>
                    </div>
                    <div style="font-weight: 700; color: #4F46E5;">
                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13.5px;">
                <span style="color: #64748B;">Total Harga Buku:</span>
                <strong>Rp {{ number_format($total_bayar, 0, ',', '.') }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 13.5px;">
                <span style="color: #64748B;">Biaya Pengiriman:</span>
                <strong style="color: #16A34A;">GRATIS</strong>
            </div>
            <div style="border-top: 1.5px solid #E2E8F0; padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 15px; font-weight: 700; color: #0F172A;">Total Tagihan:</span>
                <strong style="font-size: 20px; font-weight: 800; color: #4F46E5;">
                    Rp {{ number_format($total_bayar, 0, ',', '.') }}
                </strong>
            </div>
        </div>
    </div>
</div>

<script>
function updatePaymentView(type) {
    const qrisLabel = document.getElementById('label-qris');
    const bankLabel = document.getElementById('label-bank');
    const codLabel  = document.getElementById('label-cod');
    const infoBox   = document.getElementById('payment-info-box');

    // Reset styles
    [qrisLabel, bankLabel, codLabel].forEach(el => {
        el.style.borderColor = 'var(--border)';
        el.style.background = '#FFFFFF';
    });

    if (type === 'qris') {
        qrisLabel.style.borderColor = '#4F46E5';
        qrisLabel.style.background = '#EEF2FF';
        infoBox.innerHTML = `
            <div style="font-weight: 700; font-size: 12px; color: #4338CA; margin-bottom: 6px;">PETUNJUK PEMBAYARAN QRIS</div>
            <div style="font-size: 12.5px; color: #475569; line-height: 1.5;">
                Pindai kode QRIS Bookora menggunakan aplikasi GoPay, OVO, DANA, ShopeePay, atau Mobile Banking apa saja sebesar <strong>Rp {{ number_format($total_bayar, 0, ',', '.') }}</strong> sebelum paket buku dikirimkan.
            </div>
        `;
    } else if (type === 'bank') {
        bankLabel.style.borderColor = '#4F46E5';
        bankLabel.style.background = '#EEF2FF';
        infoBox.innerHTML = `
            <div style="font-weight: 700; font-size: 12px; color: #4338CA; margin-bottom: 6px;">PETUNJUK TRANSFER BANK</div>
            <div style="font-size: 12.5px; color: #475569; line-height: 1.5;">
                Silakan transfer sebesar <strong>Rp {{ number_format($total_bayar, 0, ',', '.') }}</strong> ke rekening Virtual Account <strong>BCA: 8720-1234-5678</strong> atas nama <strong>Bookora Official</strong>.
            </div>
        `;
    } else if (type === 'cod') {
        codLabel.style.borderColor = '#4F46E5';
        codLabel.style.background = '#EEF2FF';
        infoBox.innerHTML = `
            <div style="font-weight: 700; font-size: 12px; color: #4338CA; margin-bottom: 6px;">PETUNJUK PAYMENT AT DELIVERY (TUNAI)</div>
            <div style="font-size: 12.5px; color: #475569; line-height: 1.5;">
                Siapkan uang tunai pas sebesar <strong>Rp {{ number_format($total_bayar, 0, ',', '.') }}</strong> untuk dibayarkan kepada petugas saat buku tiba di alamat Anda.
            </div>
        `;
    }
}
</script>
@endsection
