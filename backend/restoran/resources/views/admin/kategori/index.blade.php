@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('header', 'Daftar Kategori')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 font-bold text-lg">Semua Kategori</h3>
        <a href="{{ route('admin.kategoris.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition">
            <i class="ph ph-plus"></i> Tambah Kategori
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                    <th class="p-4 border-b">Nama Kategori</th>
                    <th class="p-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($kategoris as $kategori)
                <tr class="hover:bg-gray-50 transition border-b">
                    <td class="p-4 font-bold">{{ $kategori->nama_kategori }}</td>
                    <td class="p-4 flex justify-center gap-2">
                        <a href="{{ route('admin.kategoris.edit', $kategori->id) }}" class="text-yellow-500 hover:text-yellow-600 bg-yellow-50 p-2 rounded-md transition">
                            <i class="ph ph-pencil-simple"></i>
                        </a>
                        <form action="{{ route('admin.kategoris.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Yakin hapus kategori ini?');">
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