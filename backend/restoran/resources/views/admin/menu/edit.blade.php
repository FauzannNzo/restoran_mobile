@extends('layouts.admin')

@section('title', 'Edit Menu')
@section('header', 'Edit Menu Makanan')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm">

    @if ($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
        <p class="text-sm text-red-700 font-bold">Periksa inputan Anda:</p>
        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">

            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 p-6 rounded-lg bg-gray-50 relative">

                <div class="mb-4 text-center">
                    <img id="img-preview"
                        src="{{ $menu->foto ? asset('storage/' . $menu->foto) : 'https://via.placeholder.com/150' }}"
                        alt="Preview Foto"
                        class="w-48 h-48 object-cover rounded-lg shadow-md border border-gray-200 mx-auto">

                    <p class="text-xs text-gray-500 mt-2">Foto Saat Ini</p>
                </div>

                <label class="cursor-pointer bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-full font-bold text-sm hover:bg-gray-100 transition shadow-sm">
                    <span>Ganti Foto</span>
                    <input type="file" name="foto" class="hidden" accept="image/*" onchange="previewImage(event)">
                </label>
                <p class="text-xs text-gray-400 mt-2 text-center">Biarkan kosong jika tidak ingin mengubah foto</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                <input type="text" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 transition">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">
                        <option value="">Pilih Kategori...</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}"
                            {{ old('kategori_id', $menu->kategori_id) == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', $menu->harga) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stok Saat Ini</label>
                <input type="number" name="stok_porsi" value="{{ old('stok_porsi', $menu->stok_porsi) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition w-full shadow-lg">
                Update Menu
            </button>
            <a href="{{ route('admin.menus.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-lg font-bold hover:bg-gray-200 transition w-full text-center">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        const imageField = document.getElementById("img-preview");

        reader.onload = function() {
            if (reader.readyState == 2) {
                imageField.src = reader.result;
            }
        }

        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection