<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->latest()->paginate(10);
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.menu.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok_porsi' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // Max 5MB
            'deskripsi' => 'nullable|string',
        ]);

        try {
            $data = $request->all();

            // Set default status berdasarkan stok
            $data['status'] = $request->stok_porsi > 0 ? 'tersedia' : 'habis';

            // Handle Upload Foto
            if ($request->hasFile('foto')) {
                // Simpan ke folder: storage/app/public/menus
                $path = $request->file('foto')->store('menus', 'public');
                $data['foto'] = $path;
            }

            Menu::create($data);

            return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambah menu: ' . $e->getMessage());
        }
    }

    public function edit(Menu $menu)
    {
        $kategoris = Kategori::all();
        return view('admin.menu.edit', compact('menu', 'kategoris'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'kategori_id' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        try {
            $data = $request->all();

            // Handle Update Foto
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($menu->foto && Storage::disk('public')->exists($menu->foto)) {
                    Storage::disk('public')->delete($menu->foto);
                }
                // Upload foto baru
                $data['foto'] = $request->file('foto')->store('menus', 'public');
            }

            // Update status otomatis jika stok berubah
            if ($request->filled('stok_porsi')) {
                $data['status'] = $request->stok_porsi > 0 ? 'tersedia' : 'habis';
            }

            $menu->update($data);

            return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy(Menu $menu)
    {
        // Hapus foto di folder
        if ($menu->foto && Storage::disk('public')->exists($menu->foto)) {
            Storage::disk('public')->delete($menu->foto);
        }

        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
