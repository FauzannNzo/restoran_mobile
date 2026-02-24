<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        // Urutkan berdasarkan no_meja (asc)
        $mejas = Meja::orderBy('no_meja', 'asc')->get();
        return view('admin.meja.index', compact('mejas'));
    }

    public function create()
    {
        return view('admin.meja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_meja' => 'required|string|unique:mejas,no_meja',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,booking',
        ]);

        Meja::create($request->all());

        return redirect()->route('admin.mejas.index')->with('success', 'Meja berhasil ditambahkan');
    }

    public function edit(Meja $meja)
    {
        return view('admin.meja.edit', compact('meja'));
    }

    public function update(Request $request, Meja $meja)
    {
        $request->validate([
            // Validasi unik
            'no_meja' => 'required|string|unique:mejas,no_meja,' . $meja->id,
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,booking',
        ]);

        $meja->update($request->all());

        return redirect()->route('admin.mejas.index')->with('success', 'Data meja berhasil diperbarui');
    }

    public function destroy(Meja $meja)
    {
        $meja->delete();
        return redirect()->route('admin.mejas.index')->with('success', 'Meja berhasil dihapus');
    }
}
