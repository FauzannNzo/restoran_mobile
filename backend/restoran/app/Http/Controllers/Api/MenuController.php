<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        // 1. Ambil semua menu yang statusnya 'tersedia'
        $menus = Menu::with('kategori') // Bawa data kategori juga
            ->where('status', 'tersedia')
            ->get();

        // 2. Format ulang data (PENTING UNTUK HP)
        // Kita butuh link gambar yang lengkap (http://...), bukan cuma path folder.
        $menus->transform(function ($item) {
            return [
                'id' => $item->id,
                'nama_menu' => $item->nama_menu,
                'harga' => $item->harga,
                'deskripsi' => $item->deskripsi,
                'stok' => $item->stok_porsi,
                'kategori' => $item->kategori ? $item->kategori->nama_kategori : 'Umum',
                // Ini logika supaya gambar muncul di HP:
                'gambar_url' => $item->foto ? asset('storage/' . $item->foto) : null,
            ];
        });

        // 3. Kirim respon JSON
        return response()->json([
            'success' => true,
            'message' => 'List Menu Berhasil Diambil',
            'data'    => $menus
        ], 200);
    }
}
