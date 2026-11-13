<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman_transaksi';
    protected $fillable = [
        'kode_transaksi', 'user_id', 'tanggal_pinjam', 
        'tanggal_kembali', 'tanggal_dikembalikan', 'status', 'catatan',
        'kondisi_buku', 'denda', 'alasan_suspend'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class, 'peminjaman_id');
    }
}
