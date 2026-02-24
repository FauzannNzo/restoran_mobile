<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $fillable = [
        'no_meja',
        'status',
        'kapasitas'
    ];

    public function transaksi(){
        return $this->hasMany(Transaksi::class, 'meja_id');
    }
}
