@extends('layouts.admin')

@section('title', 'Edit User')
@section('header', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $user->name }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" value="{{ $user->email }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role / Jabatan</label>
                <select name="role" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500">
                    <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    <option value="chef" {{ $user->role == 'chef' ? 'selected' : '' }}>Chef / Koki</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pelanggan" {{ $user->role == 'pelanggan' ? 'selected' : '' }}>Pelanggan (Akun Meja)</option>
                </select>
            </div>

            <div class="border-t pt-4 mt-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ganti Password (Opsional)</label>
                <input type="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 transition" placeholder="Kosongkan jika tidak ingin mengganti password">
                <p class="text-xs text-gray-500 mt-1">*Hanya isi jika ingin mengubah password user ini.</p>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition w-full shadow-lg">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-lg font-bold hover:bg-gray-200 transition w-full text-center">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection