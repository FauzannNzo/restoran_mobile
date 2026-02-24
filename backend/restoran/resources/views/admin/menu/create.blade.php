@extends('layouts.admin')

@section('title', 'Tambah Menu')
@section('header', 'Tambah Menu Baru')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm">

    @if ($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
        <p class="text-sm text-red-700 font-bold mb-2">Gagal menyimpan menu:</p>
        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 p-6 rounded-lg hover:bg-gray-50 transition relative">

                <div class="mb-4">
                    <img id="img-preview" src="#" alt="Preview Foto" class="hidden w-48 h-48 object-cover rounded-lg shadow-md border border-gray-200">
                    <div id="placeholder-icon" class="flex flex-col items-center">
                        <i class="ph ph-image text-5xl text-gray-300 mb-2"></i>
                        <p class="text-xs text-gray-400">Preview foto akan muncul di sini</p>
                    </div>
                </div>

                <label class="cursor-pointer bg-blue-50 text-blue-600 px-4 py-2 rounded-full font-bold text-sm hover:bg-blue-100 transition">
                    <span>Pilih Foto Menu</span>
                    <input type="file" name="foto" class="hidden" accept="image/*" onchange="previewImage(event)">
                </label>
                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, WEBP (Max 5MB)</p>
                @error('foto') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                <input type="text" name="nama_menu" value="{{ old('nama_menu') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 transition" placeholder="Contoh: Nasi Goreng">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">
                        <option value="">Pilih Kategori...</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stok Awal</label>
                <input type="number" name="stok_porsi" value="{{ old('stok_porsi', 10) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition w-full shadow-lg">
                Simpan Menu
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
        const placeholder = document.getElementById("placeholder-icon");

        reader.onload = function() {
            if (reader.readyState == 2) {
                imageField.src = reader.result;
                imageField.classList.remove("hidden"); // Tampilkan gambar
                placeholder.classList.add("hidden"); // Sembunyikan icon placeholder
            }
        }

        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection