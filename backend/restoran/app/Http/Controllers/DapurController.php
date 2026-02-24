<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use App\Models\Menu;

class DapurController extends Controller
{
    public function index()
    {
        // Agar tampilan di dapur terkelompok per Meja/Struk
        $pesanans = DetailTransaksi::with(['menu', 'transaksi.meja'])
            ->where('status', 'proses')
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('transaksi_id');

        return view('dapur.index', compact('pesanans'));
    }

    public function updateStatus($id)
    {
        $detail = DetailTransaksi::findOrFail($id);

        // Update status jadi 'selesai' (atau 'siap_saji' jika mau lebih detail)
        $detail->status = 'selesai';
        $detail->save();

        return redirect()->back()->with('success', 'Menu ditandai selesai/siap saji.');
    }

    // --- FITUR STOK (Tidak Berubah Banyak, sudah oke) ---
    public function stok()
    {
        $menus = Menu::orderBy('kategori_id')->get();
        return view('dapur.stok', compact('menus'));
    }

    public function updateStok(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->stok_porsi = $request->stok_porsi;

        // Otomatis ubah status jika stok 0
        $menu->status = ($request->stok_porsi > 0) ? 'tersedia' : 'habis';
        $menu->save();

        return redirect()->back()->with('success', 'Stok ' . $menu->nama_menu . ' diupdate!');
    }
}
