@extends('layouts.admin')

@section('title', 'Manajemen Menu')
@section('header', 'Daftar Menu')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 font-bold text-lg">Semua Menu</h3>
        <a href="{{ route('admin.menus.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition">
            <i class="ph ph-plus"></i> Tambah Menu
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                    <th class="p-4 border-b">Foto</th>
                    <th class="p-4 border-b">Nama Menu</th>
                    <th class="p-4 border-b">Kategori</th>
                    <th class="p-4 border-b">Harga</th>
                    <th class="p-4 border-b">Stok</th>
                    <th class="p-4 border-b">Status</th>
                    <th class="p-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($menus as $menu)
                <tr class="hover:bg-gray-50 transition border-b">
                    <td class="p-4">
                        @if($menu->foto)
                        <img src="{{ asset('storage/' . $menu->foto) }}" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                            <i class="ph ph-image text-2xl"></i>
                        </div>
                        @endif
                    </td>
                    <td class="p-4 font-bold">{{ $menu->nama_menu }}</td>
                    <td class="p-4"><span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold">{{ $menu->kategori->nama_kategori }}</span></td>
                    <td class="p-4">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                    <td class="p-4">{{ $menu->stok_porsi }} Porsi</td>
                    <td class="p-4">
                        @if($menu->status == 'tersedia')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Available</span>
                        @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Sold Out</span>
                        @endif
                    </td>
                    <td class="p-4 flex justify-center gap-2">
                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="text-yellow-500 hover:text-yellow-600 bg-yellow-50 p-2 rounded-md transition">
                            <i class="ph ph-pencil-simple"></i>
                        </a>
                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin hapus menu ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 bg-red-50 p-2 rounded-md transition">
                                <i class="ph ph-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-gray-500">
                        Belum ada menu yang ditambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $menus->links() }}
    </div>
</div>
@endsection