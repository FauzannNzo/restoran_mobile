<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = "transaksis";
    protected $fillable = [
        'nama_konsumen',
        'total_bayar',
        'tgl_transaksi',
        'status',
        'user_id',
        'meja_id',
        'metode_pembayaran'
    ];

    protected function casts(): array
    {
        return [
            'tgl_transaksi' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function meja()
    {
        return $this->belongsTo(Meja::class, 'meja_id');
    }


    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }
}
