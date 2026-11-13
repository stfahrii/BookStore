<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    public $timestamps = false;
    protected $fillable = [
        'kategori_id', 'kode_buku', 'judul', 'penulis', 'penerbit',
        'tahun_terbit', 'stok', 'harga', 'lokasi_rak', 'deskripsi', 'foto'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function cartItems()
    {
        return $this->hasMany(\App\Models\CartItem::class, 'buku_id');
    }
}
