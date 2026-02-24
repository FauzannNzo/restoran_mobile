<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'nama_menu',
        'harga',
        'kategori_id',
        'status',
        'stok_porsi',
        'foto',
        'deskripsi'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }


    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'menu_id');
    }


    public function stokHarian()
    {
        return $this->hasMany(UpdateStokHarian::class, 'menu_id');
    }
}
