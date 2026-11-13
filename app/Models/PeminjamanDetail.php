<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanDetail extends Model
{
    protected $table = 'peminjaman_detail';
    public $timestamps = false;
    protected $fillable = ['peminjaman_id', 'buku_id', 'jumlah'];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
