@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('header', 'Daftar Staff & User')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-gray-700 font-bold text-lg">Semua User</h3>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 transition">
            <i class="ph ph-plus"></i> Tambah User
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                    <th class="p-4 border-b">Nama</th>
                    <th class="p-4 border-b">Email</th>
                    <th class="p-4 border-b">Role</th>
                    <th class="p-4 border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition border-b">
                    <td class="p-4 font-bold">{{ $user->name }}</td>
                    <td class="p-4">{{ $user->email }}</td>
                    <td class="p-4">
                        @php
                        $colors = [
                        'admin' => 'bg-purple-100 text-purple-700',
                        'kasir' => 'bg-blue-100 text-blue-700',
                        'chef' => 'bg-orange-100 text-orange-700',
                        'pelanggan' => 'bg-gray-100 text-gray-700',
                        ];
                        @endphp
                        <span class="{{ $colors[$user->role] ?? 'bg-gray-100' }} px-3 py-1 rounded-full text-xs font-bold uppercase">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="p-4 flex justify-center gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-yellow-500 hover:text-yellow-600 bg-yellow-50 p-2 rounded-md transition">
                            <i class="ph ph-pencil-simple"></i>
                        </a>

                        @if(auth()->id() !== $user->id)
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 bg-red-50 p-2 rounded-md transition">
                                <i class="ph ph-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-500">Belum ada user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection