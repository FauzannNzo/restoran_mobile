<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateStokHarian extends Model
{
    protected $fillable = [
        'menu_id',
        'jumlah_porsi',
        'tgl_update'
    ];

    protected $casts = [
        'tgl_update' => 'datetime',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    } 
}