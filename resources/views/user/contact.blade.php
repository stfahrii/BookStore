@extends('layouts.user')
@section('title', 'Contact to Admin')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">
    <div class="breadcrumb">
        <a href="{{ route('user.dashboard') }}">Bookora</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Contact to Admin (Kirim Pesan)</span>
    </div>

    <h1 class="page-title">Hubungi Administrator</h1>
    <p class="page-subtitle">Kirimkan pertanyaan, permintaan judul buku, atau kendala pemesanan langsung ke pengelola.</p>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Formulir Pesan ke Admin</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('user.contact.send') }}">
                @csrf
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Anda</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->nama }}" readonly style="background: #F1F5F9; color: #475569;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly style="background: #F1F5F9; color: #475569;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Subjek / Topik Pesan <span class="req">*</span></label>
                    <input type="text" name="subjek" class="form-control" required
                           placeholder="Contoh: Pertanyaan Stok Buku, Konfirmasi Pesanan..." value="{{ old('subjek') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Isi Pesan <span class="req">*</span></label>
                    <textarea name="pesan" class="form-control" style="height: 120px; padding: 10px;" required
                              placeholder="Tuliskan pesan, pertanyaan, atau keluhan Anda secara rinci...">{{ old('pesan') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="height: 42px;">
                    Kirim Pesan ke Admin
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
