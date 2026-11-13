<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'kode_order', 'user_id', 'total_bayar', 'nama_penerima', 
        'telepon', 'alamat_pengiriman', 'metode_pembayaran', 'status_order', 'catatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
