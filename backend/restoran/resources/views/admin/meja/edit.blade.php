@extends('layouts.admin')

@section('title', 'Edit Meja')
@section('header', 'Edit Meja')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm">
    <form action="{{ route('admin.mejas.update', $meja->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Meja</label>
                <input type="text" name="no_meja" value="{{ $meja->no_meja }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas (Orang)</label>
                <input type="number" name="kapasitas" value="{{ $meja->kapasitas }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">
                    <option value="tersedia" {{ $meja->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="booking" {{ $meja->status == 'booking' ? 'selected' : '' }}>Booking / Terpakai</option>
                </select>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition w-full shadow-lg">
                Update Meja
            </button>
            <a href="{{ route('admin.mejas.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-lg font-bold hover:bg-gray-200 transition w-full text-center">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection