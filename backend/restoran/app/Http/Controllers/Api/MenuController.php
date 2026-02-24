<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        // Ambil semua menu yang statusnya 'tersedia'
        $menus = Menu::with('kategori')
            ->where('status', 'tersedia')
            ->get();

        $menus->transform(function ($item) {
            return [
                'id' => $item->id,
                'nama_menu' => $item->nama_menu,
                'harga' => $item->harga,
                'deskripsi' => $item->deskripsi,
                'stok' => $item->stok_porsi,
                'kategori' => $item->kategori ? $item->kategori->nama_kategori : 'Umum',
                'gambar_url' => $item->foto ? asset('storage/' . $item->foto) : null,
            ];
        });

        // Kirim respon JSON
        return response()->json([
            'success' => true,
            'message' => 'List Menu Berhasil Diambil',
            'data'    => $menus
        ], 200);
    }
}
