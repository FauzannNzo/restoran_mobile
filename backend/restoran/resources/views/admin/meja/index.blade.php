@extends('layouts.admin')

@section('title', 'Manajemen Meja')
@section('header', 'Daftar Meja')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 font-bold text-lg">Semua Meja</h3>
        <a href="{{ route('admin.mejas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition">
            <i class="ph ph-plus"></i> Tambah Meja
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                    <th class="p-4 border-b">Nomor Meja</th>
                    <th class="p-4 border-b">Status </th>
                    <th class="p-4 border-b">Kapasitas</th>
                    <th class="p-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($mejas as $meja)
                <tr class="hover:bg-gray-50 transition border-b">
                    <td class="p-4 font-bold">{{ $meja->no_meja }}</td>
                    <td class="p-4">
                        @if($meja->status == 'tersedia')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Tersedia</span>
                        @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Booking</span>
                        @endif
                    </td>
                    <td class="p-4">{{ $meja->kapasitas }} Orang</td>
                    <td class="p-4 flex justify-center gap-2">
                        <a href="{{ route('admin.mejas.edit', $meja->id) }}" class="text-yellow-500 hover:text-yellow-600 bg-yellow-50 p-2 rounded-md transition">
                            <i class="ph ph-pencil-simple"></i>
                        </a>
                        <form action="{{ route('admin.mejas.destroy', $meja->id) }}" method="POST" onsubmit="return confirm('Yakin hapus meja ini?');">
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
                        Belum ada kategori yang ditambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection