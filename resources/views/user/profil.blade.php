@extends('layouts.user')
@section('title', 'Profil Saya')

@section('content')
<div style="max-width: 760px; margin: 0 auto;">
    <div class="breadcrumb">
        <a href="{{ route('user.dashboard') }}">Bookora</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Profil Saya</span>
    </div>

    <h1 class="page-title">Informasi Akun Siswa</h1>
    <p class="page-subtitle">Kelola nama dan nomor telepon Anda. Data NIS dan Email bersifat unik dan permanen.</p>

    <div class="card">
        <div class="card-header">
            <span class="card-title">Edit Profil Pengguna</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('user.profil.update') }}">
                @csrf
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nomor Induk Siswa (NIS)</label>
                        <input type="text" class="form-control" value="{{ $user->nis }}" readonly
                               style="background: #F1F5F9; color: #475569; font-weight: 700; cursor: not-allowed;" title="Data permanen tidak dapat diubah">
                        <span style="font-size: 11.5px; color: #64748B; margin-top: 4px;">Permanen (Unique Identifier)</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" value="{{ $user->kelas }}" readonly
                               style="background: #F1F5F9; color: #475569; font-weight: 600; cursor: not-allowed;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Email Terdaftar</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" readonly
                           style="background: #F1F5F9; color: #475569; cursor: not-allowed;">
                    <span style="font-size: 11.5px; color: #64748B; margin-top: 4px;">Permanen untuk login</span>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" name="nama" class="form-control" required value="{{ old('nama', $user->nama) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp / HP <span class="req">*</span></label>
                        <input type="text" name="telepon" class="form-control" required
                               placeholder="Contoh: 08123456789"
                               value="{{ old('telepon', $user->telepon) }}">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 8px;">
                    <label class="form-label">Ganti Password</label>
                    <input type="password" name="password_baru" class="form-control"
                           placeholder="Kosongkan jika tidak ingin mengubah password">
                    <span style="font-size: 11.5px; color: #64748B; margin-top: 4px;">
                        Minimal 6 karakter dan mengandung angka (kosongkan jika tidak ingin diganti).
                    </span>
                </div>

                <button type="submit" class="btn btn-primary" style="height: 42px; margin-top: 10px;">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
