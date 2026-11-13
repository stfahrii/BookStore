<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. DASHBOARD / KATALOG BUKU
    public function dashboard(Request $request)
    {
        $q = $request->q;
        $kategori_id = $request->kategori;

        $buku_list = Buku::with('kategori')
            ->when($q, function ($query, $q) {
                return $query->where('judul', 'like', "%$q%")
                             ->orWhere('penulis', 'like', "%$q%")
                             ->orWhere('kode_buku', 'like', "%$q%");
            })
            ->when($kategori_id, function ($query, $kategori_id) {
                return $query->where('kategori_id', $kategori_id);
            })
            ->orderBy('id', 'desc')
            ->paginate(4)
            ->withQueryString();

        $kategori_list = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('user.dashboard', compact('buku_list', 'kategori_list', 'q', 'kategori_id'));
    }

    // 2. KERANJANG BELANJA
    public function cart()
    {
        $cart = session()->get('cart', []);
        $cart_items = [];
        $total_harga = 0;
        $has_stock_issue = false;

        if (!empty($cart)) {
            $bukus = Buku::whereIn('id', array_keys($cart))->get();
            foreach ($bukus as $buku) {
                $qty = $cart[$buku->id];
                $is_out_of_stock = ($buku->stok <= 0);
                $is_insufficient = ($buku->stok > 0 && $buku->stok < $qty);

                if ($is_out_of_stock || $is_insufficient) {
                    $has_stock_issue = true;
                }

                $subtotal = $buku->harga * $qty;
                if (!$is_out_of_stock) {
                    $total_harga += $subtotal;
                }

                $cart_items[] = [
                    'buku'            => $buku,
                    'qty'             => $qty,
                    'subtotal'        => $subtotal,
                    'is_out_of_stock' => $is_out_of_stock,
                    'is_insufficient' => $is_insufficient,
                ];
            }
        }

        return view('user.cart', compact('cart_items', 'total_harga', 'has_stock_issue'));
    }

    public function addToCart($id)
    {
        $buku = Buku::findOrFail($id);
        if ($buku->stok <= 0) {
            return back()->with('error', 'Mohon maaf, stok buku sedang habis (0).');
        }

        $cart = session()->get('cart', []);
        $cur_qty = $cart[$id] ?? 0;

        if ($cur_qty >= $buku->stok) {
            return back()->with('error', "BATAS STOK MAKSIMAL: Jumlah buku '{$buku->judul}' di keranjang sudah mencapai stok yang tersedia ({$buku->stok} eksemplar)! Pembelian tidak bisa melebihi stok.");
        }

        $cart[$id] = $cur_qty + 1;
        session()->put('cart', $cart);

        // Sinkronisasi ke DB agar Admin tahu buku ada di cart
        DB::table('cart_items')->updateOrInsert(
            ['user_id' => Auth::id(), 'buku_id' => $id],
            ['jumlah' => $cart[$id], 'qty' => $cart[$id], 'updated_at' => now()]
        );

        return redirect()->route('user.cart')->with('success', "Buku '{$buku->judul}' berhasil dimasukkan ke keranjang.");
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        foreach ($request->qty as $book_id => $qty) {
            $book_id = intval($book_id);
            $qty = intval($qty);

            if ($qty <= 0) {
                unset($cart[$book_id]);
                DB::table('cart_items')->where('user_id', Auth::id())->where('buku_id', $book_id)->delete();
            } else {
                $stok = Buku::find($book_id)->stok ?? 0;
                $cart[$book_id] = min($qty, $stok);
                DB::table('cart_items')->updateOrInsert(
                    ['user_id' => Auth::id(), 'buku_id' => $book_id],
                    ['jumlah' => $cart[$book_id], 'qty' => $cart[$book_id], 'updated_at' => now()]
                );
            }
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Keranjang belanja berhasil diperbarui.');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        DB::table('cart_items')->where('user_id', Auth::id())->where('buku_id', $id)->delete();
        return back()->with('success', 'Buku dihapus dari keranjang.');
    }

    public function clearCart()
    {
        session()->forget('cart');
        DB::table('cart_items')->where('user_id', Auth::id())->delete();
        return redirect()->route('user.cart');
    }

    // 3. CHECKOUT & PAYMENT AT DELIVERY
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart');
        }

        $bukus = Buku::whereIn('id', array_keys($cart))->get();
        $cart_items = [];
        $total_bayar = 0;

        foreach ($bukus as $buku) {
            $qty = $cart[$buku->id];

            // Validasi stok langsung jika keburu dibayar user lain
            if ($buku->stok <= 0) {
                return redirect()->route('user.cart')->with('error', "Buku '{$buku->judul}' saat ini telah habis dibeli pengguna lain. Silakan hapus buku tersebut dari keranjang.");
            }
            if ($buku->stok < $qty) {
                return redirect()->route('user.cart')->with('error', "Stok buku '{$buku->judul}' tersisa {$buku->stok} unit karena keburu dibayar pengguna lain. Silakan perbarui kuantitas.");
            }

            $subtotal = $buku->harga * $qty;
            $total_bayar += $subtotal;
            $cart_items[] = [
                'buku'     => $buku,
                'qty'      => $qty,
                'subtotal' => $subtotal
            ];
        }

        $user = Auth::user();
        return view('user.checkout', compact('cart_items', 'total_bayar', 'user'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'nama_penerima'     => 'required|string',
            'telepon'           => 'required|string',
            'alamat_pengiriman' => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart');
        }

        $bukus = Buku::whereIn('id', array_keys($cart))->get();
        $total_bayar = 0;

        foreach ($bukus as $buku) {
            $qty = $cart[$buku->id];
            if ($buku->stok < $qty) {
                return redirect()->route('user.cart')->with('error', "Gagal checkout! Buku '{$buku->judul}' keburu dibayar pengguna lain sehingga stok tidak mencukupi.");
            }
            $total_bayar += $buku->harga * $qty;
        }

        $kode_order = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 4));

        $order = Order::create([
            'kode_order'        => $kode_order,
            'user_id'           => Auth::id(),
            'total_bayar'       => $total_bayar,
            'nama_penerima'     => $request->nama_penerima,
            'telepon'           => $request->telepon,
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status_order'      => 'menunggu',
            'catatan'           => $request->catatan,
        ]);

        foreach ($bukus as $buku) {
            $qty = $cart[$buku->id];
            OrderItem::create([
                'order_id'    => $order->id,
                'buku_id'     => $buku->id,
                'jumlah'      => $qty,
                'harga'       => $buku->harga,
                'subtotal'    => $buku->harga * $qty,
            ]);

            Buku::where('id', $buku->id)->update([
                'stok' => DB::raw("GREATEST(0, stok - $qty)")
            ]);
        }

        session()->forget('cart');
        DB::table('cart_items')->where('user_id', Auth::id())->delete();

        return redirect()->route('user.pesanan_saya')->with('success', "Pesanan #$kode_order berhasil dibuat! Pembayaran dilakukan saat barang tiba.");
    }

    public function pesananSaya()
    {
        $orders = Order::with('items.buku')->where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        return view('user.pesanan_saya', compact('orders'));
    }

    // 4. PEMINJAMAN BUKU & PENGEMBALIAN (Fitur Anti-Telat, Denda & Suspend Buku Rusak)
    public function pinjamBuku($id)
    {
        // 0. Cek status akun di tabel users
        if (Auth::user()->status_akun === 'terkunci') {
            return redirect()->route('user.riwayat')->with('error', "AKUN DITANGGUHKAN (SUSPEND): Akun Anda sedang terkunci/dinonaktifkan oleh Petugas Perpustakaan karena sanksi peminjaman (buku rusak/hilang atau pelanggaran tata tertib). Silakan temui petugas perpustakaan.");
        }

        // 1. Perbarui otomatis status keterlambatan & denda
        $overdueLoans = Peminjaman::where('status', 'disetujui')
            ->where('tanggal_kembali', '<', now()->toDateString())
            ->get();
        foreach ($overdueLoans as $ol) {
            $hari = \Carbon\Carbon::parse($ol->tanggal_kembali)->diffInDays(\Carbon\Carbon::today());
            $ol->update([
                'status'         => 'terlambat',
                'denda'          => $hari * 1000,
                'alasan_suspend' => "Terlambat {$hari} hari (Denda: Rp " . number_format($hari * 1000, 0, ',', '.') . ")"
            ]);
        }

        // 2. Cek apakah user sedang kena suspend karena pinjaman terlambat, rusak, atau hilang
        $has_problem = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['terlambat', 'rusak', 'hilang'])
            ->first();
        if ($has_problem) {
            $msg = $has_problem->status === 'rusak' 
                ? "AKUN DITANGGUHKAN (SUSPEND): Anda memiliki catatan buku yang dikembalikan dalam kondisi RUSAK! Anda tidak diperkenankan meminjam sebelum melunasi ganti rugi fisik buku."
                : "AKUN DITANGGUHKAN (SUSPEND): Anda memiliki pinjaman buku yang telah melewati batas waktu pengembalian (Terlambat)! Silakan kembalikan buku fisik ke perpustakaan terlebih dahulu untuk membuka suspensi akun.";
            return redirect()->route('user.riwayat')->with('error', $msg);
        }

        // 3. Cek duplikasi peminjaman buku aktif (Anti-hoarding)
        $already_borrowed = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'disetujui', 'terlambat'])
            ->whereHas('details', function ($q) use ($id) {
                $q->where('buku_id', $id);
            })->exists();
        if ($already_borrowed) {
            return redirect()->route('user.riwayat')->with('error', "BATAS PEMINJAMAN: Anda sudah memiliki peminjaman aktif untuk buku ini yang belum selesai. Anda tidak diperkenankan meminjam judul buku yang sama secara bersamaan!");
        }

        $buku = Buku::findOrFail($id);
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku ini sedang habis (0).');
        }

        $kode_transaksi = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 4));
        $tanggal_pinjam  = now()->toDateString();
        $tanggal_kembali = now()->addDays(7)->toDateString(); // Batas waktu 7 hari

        $pinjam = Peminjaman::create([
            'kode_transaksi'  => $kode_transaksi,
            'user_id'         => Auth::id(),
            'tanggal_pinjam'  => $tanggal_pinjam,
            'tanggal_kembali' => $tanggal_kembali,
            'status'          => 'menunggu',
            'kondisi_buku'    => 'baik',
            'denda'           => 0,
            'catatan'         => 'Peminjaman via Bookora',
        ]);

        PeminjamanDetail::create([
            'peminjaman_id' => $pinjam->id,
            'buku_id'       => $buku->id,
            'jumlah'        => 1,
        ]);

        return redirect()->route('user.riwayat')->with('success', "Permintaan pinjam buku '{$buku->judul}' berhasil dikirim selama 7 hari! Tunggu konfirmasi Admin.");
    }

    public function riwayat()
    {
        // 1. Update otomatis status keterlambatan & hitung denda
        $overdueLoans = Peminjaman::where('status', 'disetujui')
            ->where('tanggal_kembali', '<', now()->toDateString())
            ->get();

        foreach ($overdueLoans as $ol) {
            $hari = \Carbon\Carbon::parse($ol->tanggal_kembali)->diffInDays(\Carbon\Carbon::today());
            $denda = $hari * 1000;
            $ol->update([
                'status'         => 'terlambat',
                'denda'          => $denda,
                'alasan_suspend' => "Terlambat {$hari} hari (Denda: Rp " . number_format($denda, 0, ',', '.') . ")"
            ]);
        }

        $user = Auth::user();
        $is_locked = ($user->status_akun === 'terkunci');
        $has_damaged = Peminjaman::where('user_id', $user->id)->where('status', 'rusak')->exists();
        $has_overdue = Peminjaman::where('user_id', $user->id)->where('status', 'terlambat')->exists();
        
        $is_suspended = $is_locked || $has_damaged || $has_overdue;
        $suspended_type = null;
        $total_denda_aktif = Peminjaman::where('user_id', $user->id)->whereIn('status', ['terlambat', 'rusak', 'hilang'])->sum('denda');

        if ($has_damaged) {
            $suspended_type = 'rusak';
        } elseif ($has_overdue) {
            $suspended_type = 'terlambat';
        } elseif ($is_locked) {
            $suspended_type = 'terkunci';
        }

        $riwayat_list = Peminjaman::with('details.buku')->where('user_id', $user->id)->orderBy('id', 'desc')->get();
        return view('user.riwayat', compact('riwayat_list', 'is_suspended', 'suspended_type', 'total_denda_aktif'));
    }

    public function kembalikanBuku(Request $request, $id)
    {
        $pinjam = Peminjaman::with(['details.buku'])->where('id', $id)->where('user_id', Auth::id())->whereIn('status', ['disetujui', 'terlambat'])->firstOrFail();
        
        $kondisi = $request->input('kondisi', $request->input('kondisi_buku', 'baik'));
        $tgl_kembali = \Carbon\Carbon::parse($pinjam->tanggal_kembali);
        $today = \Carbon\Carbon::today();
        $hari_terlambat = $today->greaterThan($tgl_kembali) ? (int)$tgl_kembali->diffInDays($today) : 0;
        $denda_terlambat = $hari_terlambat * 1000;

        if ($kondisi === 'rusak') {
            $denda_kerusakan = 0;
            foreach ($pinjam->details as $d) {
                $harga = $d->buku ? $d->buku->harga : 50000;
                $denda_kerusakan += ($harga * 0.5) * $d->jumlah;
            }
            $total_denda = $denda_terlambat + $denda_kerusakan;
            $alasan = "Buku dikembalikan dalam kondisi RUSAK. Denda keterlambatan: Rp " . number_format($denda_terlambat, 0, ',', '.') . " + Ganti rugi fisik: Rp " . number_format($denda_kerusakan, 0, ',', '.') . ". Akun disuspend!";

            // 1. User yang merusak buku LANGSUNG DISUSPEND!
            User::where('id', Auth::id())->update(['status_akun' => 'terkunci']);

            // 2. Stok buku tidak ditambah ke rak
            $pinjam->update([
                'status'               => 'rusak',
                'kondisi_buku'         => 'rusak',
                'denda'                => $total_denda,
                'alasan_suspend'       => $alasan,
                'tanggal_dikembalikan' => now()->toDateString()
            ]);

            return redirect()->route('user.riwayat')->with('error', "LAPORAN KERUSAKAN: Anda melaporkan buku dikembalikan dalam kondisi RUSAK! Sesuai tata tertib pustaka, Akun Anda OTOMATIS DISUSPEND (Terkunci). Silakan temui petugas perpustakaan untuk ganti rugi fisik buku sebesar Rp " . number_format($total_denda, 0, ',', '.') . ".");
        } else {
            // Kondisi Baik: stok bertambah
            foreach ($pinjam->details as $d) {
                Buku::where('id', $d->buku_id)->increment('stok', $d->jumlah);
            }

            $catatan_denda = "";
            if ($hari_terlambat > 0) {
                $catatan_denda = " Terlambat {$hari_terlambat} hari (Denda Keterlambatan: Rp " . number_format($denda_terlambat, 0, ',', '.') . "). Harap serahkan denda tunai kepada petugas.";
            }

            $pinjam->update([
                'status'               => 'dikembalikan',
                'kondisi_buku'         => 'baik',
                'denda'                => $denda_terlambat,
                'alasan_suspend'       => $hari_terlambat > 0 ? "Terlambat {$hari_terlambat} hari" : null,
                'tanggal_dikembalikan' => now()->toDateString()
            ]);

            return redirect()->route('user.riwayat')->with('success', "Buku pada transaksi #{$pinjam->kode_transaksi} berhasil dikembalikan dalam kondisi BAIK! Stok buku fisik telah otomatis ditambahkan kembali ke perpustakaan.{$catatan_denda}");
        }
    }

    // 5. PROFIL SAYA
    public function profil()
    {
        $user = Auth::user();
        return view('user.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'telepon'       => 'required|string|max:20',
            'password_baru' => 'nullable|min:6|regex:/[0-9]/',
        ], [
            'password_baru.regex' => 'Password baru harus mengandung minimal satu angka.',
        ]);

        $user = User::find(Auth::id());
        $user->nama    = $request->nama;
        $user->telepon = $request->telepon;

        if ($request->filled('password_baru')) {
            $user->password = Hash::make($request->password_baru);
        }

        $user->save();
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // 6. ABOUT & CONTACT
    public function about()
    {
        return view('user.about');
    }

    public function contact()
    {
        return view('user.contact');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'subjek' => 'required|string',
            'pesan'  => 'required|string',
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'nama'    => Auth::user()->nama,
            'email'   => Auth::user()->email,
            'subjek'  => $request->subjek,
            'pesan'   => $request->pesan,
            'status'  => 'belum_dibaca',
        ]);

        return back()->with('success', 'Pesan Anda berhasil dikirimkan ke Admin Bookora.');
    }
}
