@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 gap-6 mb-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl p-6 shadow-sm flex items-center border-l-4 border-green-500">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 mr-4">
                <i class="ph ph-money text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm flex items-center border-l-4 border-blue-500">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-4">
                <i class="ph ph-receipt text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Transaksi Hari Ini</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $transaksiHariIni }} <span class="text-sm font-normal text-gray-400">Pesanan</span></h3>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm flex items-center border-l-4 border-purple-500">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mr-4">
                <i class="ph ph-chair text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Meja Tersedia</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $mejaTersedia }} <span class="text-sm font-normal text-gray-400">Meja Kosong</span></h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl p-6 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Menu Makanan</p>
                <h3 class="text-xl font-bold text-gray-800">{{ $totalMenu }} Item</h3>
            </div>
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600">
                <i class="ph ph-hamburger text-xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Staff / User</p>
                <h3 class="text-xl font-bold text-gray-800">{{ $totalUser }} Akun</h3>
            </div>
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                <i class="ph ph-users text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg text-gray-800">
                {{ request('semua') ? 'Semua Transaksi' : 'Transaksi Terbaru' }}
            </h3>

            @if(request('semua'))
            <a href="{{ url()->current() }}" class="text-sm text-red-600 hover:text-red-700 font-medium">Tutup Ringkasan</a>
            @else
            <a href="?semua=true" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua</a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                        <th class="p-3 border-b">ID</th>
                        <th class="p-3 border-b">Konsumen</th>
                        <th class="p-3 border-b">Meja</th>
                        <th class="p-3 border-b">Total</th>
                        <th class="p-3 border-b">Status</th>
                        <th class="p-3 border-b">Waktu</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @forelse($latestTransaksis as $trx)
                    <tr class="hover:bg-gray-50 transition border-b">
                        <td class="p-3 font-mono text-xs">#{{ $trx->id }}</td>
                        <td class="p-3 font-semibold">{{ $trx->nama_konsumen }}</td>
                        <td class="p-3">
                            @if($trx->meja)
                            Meja {{ $trx->meja->no_meja }}
                            @else
                            <span class="text-gray-400 italic">Take Away</span>
                            @endif
                        </td>
                        <td class="p-3">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                        <td class="p-3">
                            @if($trx->status == 'dibayar')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Dibayar</span>
                            @elseif($trx->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">Pending</span>
                            @else
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Batal</span>
                            @endif
                        </td>
                        <td class="p-3 text-gray-500 text-xs">
                            {{ $trx->created_at ? $trx->created_at->diffForHumans() : 'Data lama' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-400">
                            Belum ada transaksi hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div> @if(request('semua'))
        <div class="p-4 border-t border-gray-100 mt-2">
            {{ $latestTransaksis->appends(['semua' => 'true'])->links() }}
        </div>
        @endif
    </div>
</div>

</div>
@endsection