<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Meja;
use App\Models\Menu;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validasi Input
            $request->validate([
                'nama_konsumen'     => 'required',
                'metode_pesanan'    => 'required|in:dine in,take away',
                'no_meja'           => 'nullable',
                'metode_pembayaran' => 'required',
                'total_bayar'       => 'required|numeric',
                'items'             => 'required|array'
            ]);

            // Logika Meja (Hanya cari meja jika dine-in)
            $meja_id = null; // Default null untuk Take Away

            if ($request->metode_pesanan === 'dine in') {
                $meja = Meja::where('no_meja', $request->no_meja)->first();

                if (!$meja) {
                    return response()->json(['success' => false, 'message' => 'Meja tidak ditemukan'], 404);
                }
                $meja_id = $meja->id;
            }

            // Simpan Transaksi
            $transaksi = Transaksi::create([
                'nama_konsumen'     => $request->nama_konsumen,
                'meja_id'           => $meja_id, // Akan berisi Angka (Dine-in) atau Null (Take Away)
                'total_bayar'       => $request->total_bayar,
                'status'            => 'pending',
                'metode_pembayaran' => $request->metode_pembayaran,
                'tgl_transaksi'     => now(),
                'user_id'           => null
            ]);

            // Simpan Detail Transaksi (Looping Menu)
            foreach ($request->items as $item) {
                $jumlahPesan = $item['jumlah'] ?? 1;
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id'      => $item['id'],
                    'jumlah'       => $jumlahPesan,
                    'sub_total'    => $item['subtotal'] ?? $item['harga'],
                    'status'       => 'proses',
                    'metode'       => $request->metode_pesanan,
                    'catatan'      => $item['catatan'] ?? '-'
                ]);

                // Update Stok Menu
                $menu = Menu::find($item['id']);
                if ($menu) {
                    // Kurangi stok yang ada dengan jumlah yang dipesan
                    $menu->stok_porsi = $menu->stok_porsi - $jumlahPesan;

                    // Cegah stok minus dan ubah status jika habis
                    if ($menu->stok_porsi <= 0) {
                        $menu->stok_porsi = 0;
                        $menu->status = 'habis';
                    }

                    $menu->save(); // Simpan perubahan stok ke database
                }
            }

            // Update Status Meja (jika Dine-in)
            if ($request->metode_pesanan === 'dine in' && isset($meja)) {
                $meja->update(['status' => 'booking']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesanan Berhasil Masuk!',
                'data'    => $transaksi
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'SERVER ERROR: ' . $e->getMessage()
            ], 500);
        }
    }
}
