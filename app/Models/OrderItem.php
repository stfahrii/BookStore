<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    public $timestamps = false;
    protected $fillable = ['order_id', 'buku_id', 'jumlah', 'harga', 'subtotal'];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
