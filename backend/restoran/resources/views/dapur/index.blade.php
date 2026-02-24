@extends('layouts.app')

@section('title', 'Kitchen Display')
@section('header', 'Dapur')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex gap-3">
        <h2 class="text-xl font-bold text-gray-800">Antrian Pesanan</h2>
        <span class="animate-pulse bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold flex items-center">
            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> LIVE
        </span>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('dapur.stok') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
            <i class="ph ph-list-checks text-xl"></i> Kelola Stok Harian
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($pesanans as $transaksiId => $details)
    @php $header = $details->first()->transaksi; @endphp

    <div class="bg-white rounded-xl shadow-md border-l-4 border-orange-500 overflow-hidden relative">
        <div class="bg-orange-50 p-4 border-b border-orange-100 flex justify-between items-start">
            <div>
                <h3 class="font-bold text-lg text-gray-800">
                    {{ $header->meja ? 'Meja ' . $header->meja->no_meja : 'Take Away' }}
                </h3>
                <p class="text-sm text-gray-500 font-mono">#{{ $header->id }} - {{ $header->nama_konsumen }}</p>
            </div>
            <div class="text-right">
                <span class="bg-orange-200 text-orange-800 text-xs font-bold px-2 py-1 rounded">
                    {{ $header->created_at ? $header->created_at->format('H:i') : ($header->tgl_transaksi ? $header->tgl_transaksi->format('H:i') : '-') }}
                </span>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $header->created_at ? $header->created_at->diffForHumans() : ($header->tgl_transaksi ? $header->tgl_transaksi->diffForHumans() : 'Baru saja') }}
                </p>
            </div>
        </div>

        <div class="p-4 space-y-4">
            @foreach($details as $item)
            <div class="flex justify-between items-center border-b pb-2 last:border-0 last:pb-0">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-lg text-gray-800">{{ $item->jumlah }}x</span>
                        <span class="text-gray-700 font-medium">{{ $item->menu->nama_menu }}</span>
                    </div>
                    @if($item->catatan)
                    <p class="text-red-500 text-sm italic font-bold mt-1">"{{ $item->catatan }}"</p>
                    @endif
                    <span class="text-xs text-gray-400 uppercase tracking-wider">{{ $item->metode }}</span>
                </div>

                <form action="{{ route('dapur.update', $item->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-100 text-green-700 hover:bg-green-600 hover:text-white w-10 h-10 rounded-full flex items-center justify-center transition shadow-sm" title="Selesai Masak">
                        <i class="ph ph-check text-xl font-bold"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
        <i class="ph ph-cooking-pot text-6xl mb-4"></i>
        <p class="text-xl font-medium">Tidak ada pesanan masuk.</p>
    </div>
    @endforelse
</div>

<script>
    setTimeout(function() {
        window.location.reload(1);
    }, 15000);
</script>
@endsection