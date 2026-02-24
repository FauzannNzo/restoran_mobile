<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Meja;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi yang statusnya 'pending' untuk ditampilkan di dashboard kasir
        $transaksis = Transaksi::with(['user', 'meja', 'detail'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        // Ambil transaksi yang sudah dibayar hari ini untuk ditampilkan di riwayat
        $completedTransaksis = Transaksi::where('status', 'dibayar')
            ->whereDate('created_at', today())
            ->orderBy('updated_at', 'desc')
            ->get();

        $mejas = Meja::orderBy('no_meja')->get();

        return view('kasir.index', compact('transaksis', 'completedTransaksis', 'mejas'));
    }

    public function confirmPayment(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Cek apakah transaksi masih pending, jika sudah dibayar jangan izinkan bayar lagi
        if ($transaksi->status != 'pending') {
            return redirect()->back()->with('error', 'Transaksi sudah dibayar sebelumnya!');
        }

        // Update status transaksi menjadi 'dibayar' dan simpan metode pembayaran serta user yang memproses
        $transaksi->update([
            'status' => 'dibayar',
            'metode_pembayaran' => $request->metode_pembayaran ?? 'cash',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('kasir.print', $id)->with('success', 'Pembayaran berhasil!');
    }

    public function printStruk($id)
    {
        $transaksi = Transaksi::with(['detail.menu', 'user', 'meja'])->findOrFail($id);
        return view('kasir.struk', compact('transaksi'));
    }

    public function updateMeja(Request $request, $id)
    {
        $meja = Meja::findOrFail($id);
        // Toggle status: Kalau tersedia jadi booking, kalau booking jadi tersedia
        $statusBaru = $request->status;

        $meja->update(['status' => $statusBaru]);

        return redirect()->back()->with('success', 'Status Meja ' . $meja->no_meja . ' berhasil diubah menjadi ' . $statusBaru);
    }
}
