<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// 1. Landing Page / Home Produk (Fitur Read & Search Produk)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/buku/{id}', [HomeController::class, 'detailBuku'])->name('buku.detail');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Prefix: /admin) - Diproteksi Auth + Role Admin (Akses Ditolak jika bukan admin)
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kategori Buku (Emas Lidia)
    Route::get('/kategori', [AdminController::class, 'kategori'])->name('kategori');
    Route::post('/kategori', [AdminController::class, 'storeKategori'])->name('kategori.store');
    Route::post('/kategori/{id}', [AdminController::class, 'updateKategori'])->name('kategori.update');
    Route::get('/kategori/hapus/{id}', [AdminController::class, 'destroyKategori'])->name('kategori.destroy');

    // Kelola Buku
    Route::get('/buku', [AdminController::class, 'buku'])->name('buku');
    Route::post('/buku', [AdminController::class, 'storeBuku'])->name('buku.store');
    Route::post('/buku/{id}', [AdminController::class, 'updateBuku'])->name('buku.update');
    Route::get('/buku/hapus/{id}', [AdminController::class, 'destroyBuku'])->name('buku.destroy');

    // List Pesanan (Info/Detail)
    Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('pesanan');
    Route::post('/pesanan/status/{id}', [AdminController::class, 'updateStatusPesanan'])->name('pesanan.status');

    // Peminjaman Per User (Setujui Semua)
    Route::get('/peminjaman', [AdminController::class, 'peminjaman'])->name('peminjaman');
    Route::get('/peminjaman/setujui/{id}', [AdminController::class, 'setujuiPeminjaman'])->name('peminjaman.setujui');
    Route::get('/peminjaman/kembalikan/{id}', [AdminController::class, 'kembalikanPeminjaman'])->name('peminjaman.kembalikan');
    Route::get('/peminjaman/tolak/{id}', [AdminController::class, 'tolakPeminjaman'])->name('peminjaman.tolak');

    // List User Terdaftar (Kunci Akun On/Off)
    Route::get('/anggota', [AdminController::class, 'anggota'])->name('anggota');
    Route::get('/anggota/toggle/{id}', [AdminController::class, 'toggleAkun'])->name('anggota.toggle');

    // Pesan Contact Us
    Route::get('/pesan', [AdminController::class, 'pesan'])->name('pesan');
    Route::get('/pesan/baca/{id}', [AdminController::class, 'bacaPesan'])->name('pesan.baca');
});

// User / Siswa Routes (Prefix: /user)
Route::get('/user', function () {
    return redirect()->route('user.dashboard');
});
Route::middleware(['auth', 'role:anggota'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Keranjang Belanja (Cart)
    Route::get('/cart', [UserController::class, 'cart'])->name('cart');
    Route::get('/cart/add/{id}', [UserController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [UserController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart/remove/{id}', [UserController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart/clear', [UserController::class, 'clearCart'])->name('cart.clear');

    // Checkout & Payment at Delivery
    Route::get('/checkout', [UserController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [UserController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/pesanan-saya', [UserController::class, 'pesananSaya'])->name('pesanan_saya');

    // Peminjaman Mandiri & Pengembalian Buku
    Route::get('/pinjam/{id}', [UserController::class, 'pinjamBuku'])->name('pinjam');
    Route::get('/riwayat', [UserController::class, 'riwayat'])->name('riwayat');
    Route::get('/riwayat/kembalikan/{id}', [UserController::class, 'kembalikanBuku'])->name('riwayat.kembalikan');

    // Profil Siswa (Edit No HP, NIS & Email Permanen)
    Route::get('/profil', [UserController::class, 'profil'])->name('profil');
    Route::post('/profil', [UserController::class, 'updateProfil'])->name('profil.update');

    // About Us & Contact to Admin
    Route::get('/about', [UserController::class, 'about'])->name('about');
    Route::get('/contact', [UserController::class, 'contact'])->name('contact');
    Route::post('/contact', [UserController::class, 'sendMessage'])->name('contact.send');
});
