<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Menu;
use App\Models\User;
use App\Models\Meja;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Total Pendapatan (Hanya yang status 'dibayar')
        $totalPendapatan = Transaksi::where('status', 'dibayar')->sum('total_bayar');

        // Transaksi Hari Ini
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();

        // Jumlah Menu & User
        $totalMenu = Menu::count();
        $totalUser = User::count();

        // Meja Tersedia
        $mejaTersedia = Meja::where('status', 'tersedia')->count();

        if ($request->has('semua')) {
            // Munculkan semua dengan pagination (misal 10 data per halaman)
            $latestTransaksis = Transaksi::latest()->paginate(10);
        } else {
            // Default: Cuma tampilkan 5 transaksi terbaru untuk ringkasan
            $latestTransaksis = Transaksi::latest()->take(5)->get();
        }

        return view('admin.index', compact(
            'totalPendapatan',
            'transaksiHariIni',
            'totalMenu',
            'totalUser',
            'mejaTersedia',
            'latestTransaksis'
        ));
    }
}
