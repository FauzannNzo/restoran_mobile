@extends('layouts.app')

@section('title', 'Kelola Stok Harian')
@section('header', 'Update Stok Menu')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 max-w-4xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-gray-700 text-lg">Daftar Stok Menu</h3>
        <a href="{{ route('dapur.index') }}" class="text-gray-500 hover:text-gray-800 flex items-center gap-2">
            <i class="ph ph-arrow-left"></i> Kembali ke Antrian
        </a>
    </div>

    <div class="mb-4">
        <input type="text" id="searchInput" onkeyup="filterMenu()" placeholder="Cari nama menu..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-200">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="stokTable">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                    <th class="p-4 border-b">Menu</th>
                    <th class="p-4 border-b">Kategori</th>
                    <th class="p-4 border-b">Status Saat Ini</th>
                    <th class="p-4 border-b" style="width: 200px;">Update Stok</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach($menus as $menu)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-4 font-bold flex items-center gap-3">
                        @if($menu->foto)
                        <img src="{{ asset('storage/' . $menu->foto) }}" class="w-10 h-10 rounded-full object-cover border">
                        @else
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <i class="ph ph-image"></i>
                        </div>
                        @endif
                        {{ $menu->nama_menu }}
                    </td>
                    <td class="p-4">
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold">{{ $menu->kategori->nama_kategori }}</span>
                    </td>
                    <td class="p-4">
                        @if($menu->stok_porsi > 0)
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Tersedia ({{ $menu->stok_porsi }})</span>
                        @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Habis</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <form action="{{ route('dapur.updateStok', $menu->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="number" name="stok_porsi" value="{{ $menu->stok_porsi }}" min="0" class="w-20 border-gray-300 rounded-lg text-center p-1 focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg transition" title="Simpan">
                                <i class="ph ph-floppy-disk"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function filterMenu() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("stokTable");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0]; 
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
@endsection