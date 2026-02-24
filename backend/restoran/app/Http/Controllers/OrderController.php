<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Meja;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nomor meja dari URL
        // Kalau tidak ada, default null
        $noMeja = $request->query('meja');

        // Ambil semua kategori beserta menunya
        // Kita eager load 'menus' biar query database ringan
        $kategoris = Kategori::with(['menu' => function ($query) {
            $query->where('status', 'tersedia')
                ->where('stok_porsi', '>', 0); // Hanya menu yang ready
        }])->get();

        $mejas = Meja::orderBy('no_meja', 'asc')->get();

        return view('order.index', compact('kategoris', 'noMeja', 'mejas'));
    }

    // Proses Simpan Pesanan
    public function store(Request $request)
    {
        // 1. Validasi (Samakan kata 'tunai' atau 'cash' sesuai database)
        $request->validate([
            'nama_konsumen'     => 'required|string|max:255',
            'meja_id'           => 'nullable',
            'items'             => 'required|array',
            'metode_pembayaran' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            // 2. Buat Transaksi
            $transaksi = Transaksi::create([
                'nama_konsumen'     => $request->nama_konsumen,
                'meja_id'           => $request->meja_id,
                'user_id'           => null,
                'status'            => 'pending',
                'total_bayar'       => 0,
                'metode_pembayaran' => $request->metode_pembayaran ?? 'cash', // Default tunai
                'tgl_transaksi'     => now(),
            ]);

            $totalBayar = 0;

            foreach ($request->items as $item) {
                $menu = Menu::find($item['id']);

                // Cek kunci 'jumlah' atau 'qty' agar fleksibel
                $qty = $item['jumlah'] ?? $item['qty'] ?? 1;

                if ($menu->stok_porsi < $qty) {
                    throw new \Exception("Stok menu {$menu->nama_menu} tidak cukup.");
                }

                $subTotal = $menu->harga * $qty;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id'      => $menu->id,
                    'jumlah'       => $qty,
                    'sub_total'    => $subTotal,
                    'status'       => 'proses',
                    'metode'       => $request->meja_id ? 'dine in' : 'take away',
                    'catatan'      => $item['catatan'] ?? '-',
                ]);

                $menu->decrement('stok_porsi', $qty);
                $totalBayar += $subTotal;
            }

            $transaksi->update(['total_bayar' => $totalBayar]);

            if ($request->meja_id) {
                Meja::where('id', $request->meja_id)->update(['status' => 'terisi']);
            }

            DB::commit();

            // 3. Respon Pintar: Jika AJAX kirim JSON, jika Form biasa kirim Redirect
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan Berhasil!',
                    'redirect' => route('order.success', $transaksi->id)
                ]);
            }

            return redirect()->route('order.success', $transaksi->id);
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    // Halaman Sukses (Struk Sementara)
    public function success($id)
    {
        $transaksi = Transaksi::with('detail.menu')->findOrFail($id);
        return view('order.success', compact('transaksi'));
    }
}
