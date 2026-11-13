<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index(Request $request)
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

        $kategori_list = Kategori::withCount('bukus')->orderBy('nama_kategori', 'asc')->get();
        $total_buku = Buku::count();

        return view('landing', compact('buku_list', 'kategori_list', 'q', 'kategori_id', 'total_buku'));
    }

    public function detailBuku($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('detail_buku', compact('buku'));
    }
}
