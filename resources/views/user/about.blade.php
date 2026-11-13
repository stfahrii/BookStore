@extends('layouts.user')
@section('title', 'About Us')

@section('content')
<div class="breadcrumb">
    <a href="{{ route('user.dashboard') }}">Bookora</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Melihat detail halaman About Us</span>
</div>

<h1 class="page-title">Tentang Bookora</h1>
<p class="page-subtitle">Informasi profil, visi misi, dan layanan toko buku online & peminjaman pustaka.</p>

<div class="card">
    <div class="card-body" style="padding: 32px;">
        <div style="max-width: 800px;">
            <div style="display: inline-block; background: #EEF2FF; color: #4F46E5; font-weight: 700; font-size: 12px; padding: 4px 12px; border-radius: 99px; text-transform: uppercase; margin-bottom: 12px;">
                Profil BookStore Modern
            </div>
            <h2 style="font-size: 22px; font-weight: 800; color: #0F172A; margin-bottom: 16px;">
                Platform Literasi & Penjualan Buku Terpadu
            </h2>
            <p style="font-size: 15px; line-height: 1.7; color: #475569; margin-bottom: 20px;">
                <strong>Bookora</strong> dibangun di atas arsitektur <em>Laravel 11 MVC</em> untuk memberikan pengalaman terbaik bagi pecinta buku dan siswa sekolah dalam berliterasi. Kami menggabungkan dua ekosistem sekaligus: kemudahan berbelanja buku resmi (BookStore) dan kemudahan peminjaman referensi pelajaran secara gratis.
            </p>

            <div class="form-grid-3" style="margin: 28px 0; gap: 20px;">
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 20px; border-radius: 12px;">
                    <div style="font-weight: 700; color: #0F172A; margin-bottom: 6px; font-size: 15px;">Koleksi Lengkap</div>
                    <p style="font-size: 13px; color: #64748B; line-height: 1.5;">Menyediakan ribuan judul buku pemrograman, sains, sastra, sejarah, hingga fiksi.</p>
                </div>
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 20px; border-radius: 12px;">
                    <div style="font-weight: 700; color: #0F172A; margin-bottom: 6px; font-size: 15px;">Payment at Delivery</div>
                    <p style="font-size: 13px; color: #64748B; line-height: 1.5;">Metode pembayaran terpercaya sebelum pesanan buku dikirimkan ke alamat Anda.</p>
                </div>
                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 20px; border-radius: 12px;">
                    <div style="font-weight: 700; color: #0F172A; margin-bottom: 6px; font-size: 15px;">Layanan Responsif</div>
                    <p style="font-size: 13px; color: #64748B; line-height: 1.5;">Fitur Contact to Admin terintegrasi untuk komunikasi interaktif pengunjung dan petugas.</p>
                </div>
            </div>

            <div style="border-top: 1px solid #E2E8F0; padding-top: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Jelajahi Katalog Buku</a>
                <a href="{{ route('user.contact') }}" class="btn btn-secondary">Hubungi Admin (Contact Us)</a>
            </div>
        </div>
    </div>
</div>
@endsection
