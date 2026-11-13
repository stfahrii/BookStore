<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // 1. DASHBOARD
    public function dashboard()
    {
        $total_buku    = Buku::count();
        $total_omset   = Order::where('status_order', '!=', 'dibatalkan')->sum('total_bayar');
        $total_anggota = User::where('role', 'anggota')->count();
        $total_order   = Order::count();
        $recent_orders = Order::with('user')->orderBy('id', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('total_buku', 'total_omset', 'total_anggota', 'total_order', 'recent_orders'));
    }

    // 2. KELOLA KATEGORI (Logika Emas Lidia)
    public function kategori(Request $request)
    {
        $edit_data = null;
        if ($request->has('edit')) {
            $edit_data = Kategori::find($request->edit);
        }

        $kategori_list = Kategori::withCount('bukus')
            ->withSum('bukus', 'stok')
            ->orderBy('nama_kategori', 'asc')
            ->get();

        return view('admin.kategori', compact('kategori_list', 'edit_data'));
    }

    public function storeKategori(Request $request)
    {
        $nama = trim($request->nama_kategori ?? '');
        $check = Kategori::whereRaw('LOWER(TRIM(nama_kategori)) = ?', [strtolower($nama)])->exists();
        if ($check) {
            return back()->with('error', "GAGAL DUPLIKAT: Kategori '$nama' sudah ada sebelumnya! Tidak boleh menambahkan kategori yang sama.");
        }

        $request->validate(['nama_kategori' => 'required|string|max:100']);
        Kategori::create(['nama_kategori' => $nama]);
        return redirect()->route('admin.kategori')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, $id)
    {
        $nama = trim($request->nama_kategori ?? '');
        $check = Kategori::where('id', '!=', $id)
            ->whereRaw('LOWER(TRIM(nama_kategori)) = ?', [strtolower($nama)])
            ->exists();
        if ($check) {
            return back()->with('error', "GAGAL DUPLIKAT: Nama kategori '$nama' sudah digunakan oleh kategori lain!");
        }

        $request->validate(['nama_kategori' => 'required|string|max:100']);
        $kategori = Kategori::findOrFail($id);
        $kategori->update(['nama_kategori' => $nama]);
        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyKategori($id)
    {
        $kategori = Kategori::withCount('bukus')->findOrFail($id);
        if ($kategori->bukus_count > 0) {
            return back()->with('error', "TIDAK BISA DIHAPUS! Kategori ini masih memiliki {$kategori->bukus_count} judul buku. Pindahkan atau hapus buku terlebih dahulu.");
        }
        $kategori->delete();
        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil dihapus.');
    }

    // 3. KELOLA BUKU & FOTO (Stok anti-minus, Proteksi Hapus Cart)
    public function buku(Request $request)
    {
        $q = $request->q;
        $edit_data = null;
        if ($request->has('edit')) {
            $edit_data = Buku::find($request->edit);
        }

        $buku_list = Buku::with('kategori')
            ->when($q, function ($query, $q) {
                return $query->where('judul', 'like', "%$q%")
                             ->orWhere('penulis', 'like', "%$q%")
                             ->orWhere('kode_buku', 'like', "%$q%");
            })
            ->orderBy('id', 'desc')
            ->paginate(4)
            ->withQueryString();

        $kategori_list = Kategori::orderBy('nama_kategori', 'asc')->get();
        $next_kode = 'BK-' . str_pad((Buku::max('id') ?? 0) + 1, 3, '0', STR_PAD_LEFT);

        return view('admin.buku', compact('buku_list', 'kategori_list', 'edit_data', 'q', 'next_kode'));
    }

    public function storeBuku(Request $request)
    {
        $judul   = trim($request->judul ?? '');
        $penulis = trim($request->penulis ?? '');

        // Validasi Duplikat: Judul & Penulis sama persis
        $dup = Buku::whereRaw('LOWER(TRIM(judul)) = ? AND LOWER(TRIM(penulis)) = ?', [strtolower($judul), strtolower($penulis)])->first();
        if ($dup) {
            return back()->withInput()->with('error', "GAGAL DUPLIKAT: Buku dengan judul '$judul' karya '$penulis' sudah terdaftar di sistem! Tidak boleh menambahkan buku yang sama.");
        }

        $request->validate([
            'kode_buku'    => 'required|string|max:50|unique:buku,kode_buku',
            'judul'        => 'required|string|max:150',
            'kategori_id'  => 'required|exists:kategori,id',
            'penulis'      => 'required|string|max:100',
            'penerbit'     => 'required|string|max:100',
            'tahun_terbit' => 'required|numeric|min:1900|max:' . (date('Y') + 5),
            'stok'         => 'required|numeric|min:0',
            'harga'        => 'required|numeric|min:0',
            'lokasi_rak'   => 'nullable|string|max:50',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif,avif|max:10240',
        ], [
            'kode_buku.unique'    => 'Kode buku tersebut sudah digunakan. Gunakan kode yang unik.',
            'kategori_id.exists'  => 'Kategori yang dipilih tidak valid.',
            'tahun_terbit.min'    => 'Tahun terbit minimal 1900.',
            'tahun_terbit.max'    => 'Tahun terbit melebihi batas yang wajar.',
            'foto.image'          => 'File sampul harus berupa format gambar yang valid.',
            'foto.max'            => 'Ukuran file foto maksimal 10 MB.',
        ]);

        $foto_name = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $foto_name = 'buku_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $foto_name);
        }

        Buku::create([
            'kode_buku'    => $request->kode_buku,
            'judul'        => $request->judul,
            'kategori_id'  => $request->kategori_id,
            'penulis'      => $request->penulis,
            'penerbit'     => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'stok'         => max(0, $request->stok),
            'harga'        => max(0, $request->harga),
            'lokasi_rak'   => !empty($request->lokasi_rak) ? $request->lokasi_rak : 'Rak A-1',
            'deskripsi'    => $request->deskripsi,
            'foto'         => $foto_name,
        ]);

        return redirect()->route('admin.buku')->with('success', "Buku '{$request->judul}' berhasil ditambahkan.");
    }

    public function updateBuku(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $judul   = trim($request->judul ?? '');
        $penulis = trim($request->penulis ?? '');

        // Validasi Duplikat saat update
        $dup = Buku::where('id', '!=', $id)
            ->whereRaw('LOWER(TRIM(judul)) = ? AND LOWER(TRIM(penulis)) = ?', [strtolower($judul), strtolower($penulis)])
            ->first();
        if ($dup) {
            return back()->withInput()->with('error', "GAGAL DUPLIKAT: Judul '$judul' karya '$penulis' sudah terdaftar pada buku lain!");
        }

        $request->validate([
            'kode_buku'    => 'required|string|max:50|unique:buku,kode_buku,' . $id,
            'judul'        => 'required|string|max:150',
            'kategori_id'  => 'required|exists:kategori,id',
            'penulis'      => 'required|string|max:100',
            'penerbit'     => 'required|string|max:100',
            'tahun_terbit' => 'required|numeric|min:1900|max:' . (date('Y') + 5),
            'stok'         => 'required|numeric|min:0',
            'harga'        => 'required|numeric|min:0',
            'lokasi_rak'   => 'nullable|string|max:50',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif,avif|max:10240',
        ], [
            'kode_buku.unique'    => 'Kode buku tersebut sudah digunakan oleh buku lain.',
            'kategori_id.exists'  => 'Kategori yang dipilih tidak valid.',
            'tahun_terbit.min'    => 'Tahun terbit minimal 1900.',
            'tahun_terbit.max'    => 'Tahun terbit melebihi batas yang wajar.',
            'foto.image'          => 'File sampul harus berupa format gambar yang valid.',
            'foto.max'            => 'Ukuran file foto maksimal 10 MB.',
        ]);

        $foto_name = $buku->foto;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $foto_name = 'buku_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $foto_name);
        }

        $buku->update([
            'kode_buku'    => $request->kode_buku,
            'judul'        => $request->judul,
            'kategori_id'  => $request->kategori_id,
            'penulis'      => $request->penulis,
            'penerbit'     => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'stok'         => max(0, $request->stok),
            'harga'        => max(0, $request->harga),
            'lokasi_rak'   => !empty($request->lokasi_rak) ? $request->lokasi_rak : ($buku->lokasi_rak ?: 'Rak A-1'),
            'deskripsi'    => $request->deskripsi,
            'foto'         => $foto_name,
        ]);

        return redirect()->route('admin.buku')->with('success', "Data buku '{$request->judul}' berhasil diperbarui.");
    }

    public function destroyBuku($id)
    {
        $in_cart = DB::table('cart_items')->where('buku_id', $id)->count();
        if ($in_cart > 0) {
            return back()->with('error', "TIDAK BISA DIHAPUS! Buku ini sedang ada di keranjang belanja $in_cart pengguna.");
        }
        Buku::destroy($id);
        return redirect()->route('admin.buku')->with('success', 'Buku berhasil dihapus dari katalog.');
    }

    // 4. LIST PESANAN USER
    public function pesanan(Request $request)
    {
        $filter = $request->status ?? 'semua';
        $order_list = Order::with(['user', 'items.buku'])
            ->when($filter !== 'semua', function ($query) use ($filter) {
                return $query->where('status_order', $filter);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.pesanan', compact('order_list', 'filter'));
    }

    public function updateStatusPesanan(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Alur status pengiriman searah ala e-commerce (Shopee/Tokopedia)
        // Tidak boleh dibatalkan sepihak jika sudah bayar, dan tidak boleh dimundurkan
        $validTransitions = [
            'menunggu' => 'diproses',
            'diproses' => 'dikirim',
            'dikirim'  => 'selesai',
        ];

        $currentStatus = $order->status_order;
        $nextStatus = $request->status_order;

        if ($currentStatus === 'selesai') {
            return back()->with('error', "Pesanan #{$order->kode_order} sudah SELESAI dan status terkunci secara permanen.");
        }

        if (!isset($validTransitions[$currentStatus]) || $validTransitions[$currentStatus] !== $nextStatus) {
            return back()->with('error', "Alur status tidak valid! Status harus maju bertahap: Menunggu -> Diproses -> Dikirim -> Selesai.");
        }

        $order->update(['status_order' => $nextStatus]);

        $statusLabels = [
            'diproses' => 'Sedang Diproses (Dikemas)',
            'dikirim'  => 'Sedang Dikirim ke Pembeli',
            'selesai'  => 'Selesai / Diterima Pembeli',
        ];
        $label = $statusLabels[$nextStatus] ?? $nextStatus;

        return back()->with('success', "Status pesanan #{$order->kode_order} berhasil diperbarui menjadi: {$label}.");
    }

    // 5. PEMINJAMAN BUKU (Setujui Semua per Transaksi & Verifikasi Pengembalian Kondisi)
    public function peminjaman(Request $request)
    {
        // Otomatis hitung hari terlambat & denda bagi yang lewat tanggal_kembali
        $overdueLoans = Peminjaman::where('status', 'disetujui')
            ->where('tanggal_kembali', '<', now()->toDateString())
            ->get();

        foreach ($overdueLoans as $ol) {
            $hari = \Carbon\Carbon::parse($ol->tanggal_kembali)->diffInDays(\Carbon\Carbon::today());
            $denda = $hari * 1000; // Rp 1.000 / hari
            $ol->update([
                'status'         => 'terlambat',
                'denda'          => $denda,
                'alasan_suspend' => "Terlambat {$hari} hari (Denda: Rp " . number_format($denda, 0, ',', '.') . ")"
            ]);
        }

        $filter = $request->status ?? 'semua';
        $pinjam_list = Peminjaman::with(['user', 'details.buku'])
            ->when($filter !== 'semua', function ($query) use ($filter) {
                return $query->where('status', $filter);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.peminjaman', compact('pinjam_list', 'filter'));
    }

    public function setujuiPeminjaman($id)
    {
        $peminjaman = Peminjaman::with(['details', 'user'])->findOrFail($id);
        
        // Cek apakah user yang meminjam sedang berstatus terkunci/suspend
        if ($peminjaman->user && $peminjaman->user->status_akun === 'terkunci') {
            return back()->with('error', "GAGAL: Peminjam ({$peminjaman->user->nama}) sedang berstatus DISUSPEND (Terkunci) karena sanksi! Tidak bisa menyetujui pinjaman baru sebelum suspend dicabut.");
        }

        if ($peminjaman->status === 'menunggu') {
            foreach ($peminjaman->details as $d) {
                Buku::where('id', $d->buku_id)->update([
                    'stok' => DB::raw("GREATEST(0, stok - {$d->jumlah})")
                ]);
            }
            $peminjaman->update(['status' => 'disetujui']);
            return back()->with('success', "Peminjaman #{$peminjaman->kode_transaksi} berhasil DISETUJUI.");
        }
        return back();
    }

    public function kembalikanPeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::with(['details.buku', 'user'])->findOrFail($id);
        if (!in_array($peminjaman->status, ['disetujui', 'terlambat'])) {
            return back()->with('error', 'Status transaksi tidak valid untuk proses pengembalian.');
        }

        $kondisi = $request->input('kondisi', $request->input('kondisi_buku', 'baik'));
        $tgl_kembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
        $today = \Carbon\Carbon::today();
        
        $hari_terlambat = $today->greaterThan($tgl_kembali) ? (int)$tgl_kembali->diffInDays($today) : 0;
        $denda_terlambat = $hari_terlambat * 1000; // Rp 1.000 / hari
        $denda_kerusakan = 0;

        if ($kondisi === 'rusak') {
            // Hitung denda ganti rugi fisik buku rusak (50% dari harga buku)
            foreach ($peminjaman->details as $d) {
                $harga = $d->buku ? $d->buku->harga : 50000;
                $denda_kerusakan += ($harga * 0.5) * $d->jumlah;
            }
            $total_denda = $denda_terlambat + $denda_kerusakan;
            $alasan = "Buku dikembalikan dalam kondisi RUSAK. Denda keterlambatan: Rp " . number_format($denda_terlambat, 0, ',', '.') . " + Ganti rugi fisik buku: Rp " . number_format($denda_kerusakan, 0, ',', '.') . ". Akun disuspend!";

            // 1. Akun peminjam LANGSUNG DISUSPEND!
            User::where('id', $peminjaman->user_id)->update(['status_akun' => 'terkunci']);

            // 2. Stok buku TIDAK bertambah ke rak siap pinjam
            $peminjaman->update([
                'status'               => 'rusak',
                'kondisi_buku'         => 'rusak',
                'denda'                => $total_denda,
                'alasan_suspend'       => $alasan,
                'tanggal_dikembalikan' => now()->toDateString()
            ]);

            return back()->with('error', "PENALTI BUKU RUSAK: Peminjaman #{$peminjaman->kode_transaksi} dicatat RUSAK! Akun peminjam ({$peminjaman->user->nama}) OTOMATIS DISUSPEND dengan kewajiban ganti rugi Rp " . number_format($total_denda, 0, ',', '.') . ". Stok buku tidak ditambahkan ke rak.");
        } elseif ($kondisi === 'hilang') {
            // Denda seharga penuh 100% buku
            foreach ($peminjaman->details as $d) {
                $harga = $d->buku ? $d->buku->harga : 60000;
                $denda_kerusakan += $harga * $d->jumlah;
            }
            $total_denda = $denda_terlambat + $denda_kerusakan;
            $alasan = "Buku dinyatakan HILANG. Denda keterlambatan: Rp " . number_format($denda_terlambat, 0, ',', '.') . " + Biaya penggantian buku: Rp " . number_format($denda_kerusakan, 0, ',', '.') . ". Akun disuspend!";

            // 1. Akun peminjam LANGSUNG DISUSPEND!
            User::where('id', $peminjaman->user_id)->update(['status_akun' => 'terkunci']);

            // 2. Stok buku tidak bertambah
            $peminjaman->update([
                'status'               => 'hilang',
                'kondisi_buku'         => 'hilang',
                'denda'                => $total_denda,
                'alasan_suspend'       => $alasan,
                'tanggal_dikembalikan' => now()->toDateString()
            ]);

            return back()->with('error', "PENALTI KEHILANGAN: Buku pada transaksi #{$peminjaman->kode_transaksi} dinyatakan HILANG! Akun siswa ({$peminjaman->user->nama}) OTOMATIS DISUSPEND dengan kewajiban ganti rugi Rp " . number_format($total_denda, 0, ',', '.') . ".");
        } else {
            // Kondisi Baik:
            // Stok bertambah kembali ke rak
            foreach ($peminjaman->details as $d) {
                Buku::where('id', $d->buku_id)->increment('stok', $d->jumlah);
            }

            $catatan_tambahan = "";
            if ($hari_terlambat > 0) {
                $catatan_tambahan = " Dikembalikan terlambat {$hari_terlambat} hari (Denda keterlambatan: Rp " . number_format($denda_terlambat, 0, ',', '.') . ").";
            }

            $peminjaman->update([
                'status'               => 'kembali',
                'kondisi_buku'         => 'baik',
                'denda'                => $denda_terlambat,
                'alasan_suspend'       => $hari_terlambat > 0 ? "Terlambat {$hari_terlambat} hari" : null,
                'tanggal_dikembalikan' => now()->toDateString()
            ]);

            return back()->with('success', "Peminjaman #{$peminjaman->kode_transaksi} berhasil DIKEMBALIKAN dalam kondisi BAIK. Stok buku bertambah kembali.{$catatan_tambahan}");
        }
    }

    public function tolakPeminjaman($id)
    {
        Peminjaman::where('id', $id)->update(['status' => 'ditolak']);
        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    // 6. LIST ANGGOTA (Kunci Akun On/Off)
    public function anggota(Request $request)
    {
        $q = $request->q;
        $anggota_list = User::where('role', 'anggota')
            ->when($q, function ($query, $q) {
                return $query->where('nama', 'like', "%$q%")
                             ->orWhere('nis', 'like', "%$q%")
                             ->orWhere('email', 'like', "%$q%");
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.anggota', compact('anggota_list', 'q'));
    }

    public function toggleAkun($id)
    {
        $user = User::findOrFail($id);
        $status_baru = ($user->status_akun === 'aktif') ? 'terkunci' : 'aktif';
        $user->update(['status_akun' => $status_baru]);
        return back()->with('success', "Status akun {$user->nama} diubah menjadi: " . strtoupper($status_baru));
    }

    // 7. PESAN USER (Contact to Admin)
    public function pesan()
    {
        $messages = Message::orderBy('id', 'desc')->get();
        return view('admin.pesan', compact('messages'));
    }

    public function bacaPesan($id)
    {
        Message::where('id', $id)->update(['status' => 'dibaca']);
        return back()->with('success', 'Pesan telah ditandai sebagai sudah dibaca.');
    }
}
