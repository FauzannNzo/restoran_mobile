<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - RasaNyaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden relative">

            <div class="absolute top-0 left-0 right-0 h-2 bg-blue-600"></div>

            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph-fill ph-check-circle text-5xl"></i>
                </div>

                <h1 class="text-2xl font-extrabold text-gray-900">Pesanan Diterima!</h1>
                <p class="text-gray-500 text-sm mt-2">Silakan melakukan pembayaran dan sebutkan nama Anda saat membayar di kasir.</p>
            </div>

            <div class="px-8 pb-8">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <div class="flex justify-between mb-4">
                        <span class="text-xs font-bold text-gray-400 uppercase">Pelanggan</span>
                        <span class="text-sm font-bold text-gray-800">{{ $transaksi->nama_konsumen }}</span>
                    </div>
                    <div class="flex justify-between mb-4">
                        <span class="text-xs font-bold text-gray-400 uppercase">Meja / Tipe</span>
                        <span class="text-sm font-bold text-gray-800">
                            {{ $transaksi->meja ? 'Meja ' . $transaksi->meja->no_meja : 'Take Away' }}
                        </span>
                    </div>
                    <div class="flex justify-between mb-4">
                        <span class="text-xs font-bold text-gray-400 uppercase">Waktu</span>
                        <span class="text-sm font-bold text-gray-800">{{ $transaksi->created_at->format('H:i') }} WIB</span>
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-4"></div>

                    <div class="space-y-3">
                        @foreach($transaksi->detail as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600"><span class="font-bold text-gray-800">{{ $item->jumlah }}x</span> {{ $item->menu->nama_menu }}</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($item->sub_total, 0, ',', '.') }}</span>
                        </div>
                        @if($item->catatan && $item->catatan !== '-')
                        <p class="text-[10px] text-orange-500 italic mt-[-8px]">- {{ $item->catatan }}</p>
                        @endif
                        @endforeach
                    </div>

                    <div class="border-t border-dashed border-gray-300 my-4"></div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-900">Total Pembayaran</span>
                        <span class="text-xl font-black text-blue-600">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-8 space-y-3">
                    <div class="bg-blue-50 text-blue-700 p-4 rounded-2xl border border-blue-100 flex items-start gap-3 text-left">
                        <i class="ph-fill ph-info text-xl"></i>
                        <p class="text-xs leading-relaxed">
                            Pesanan Anda telah diteruskan ke dapur. Silakan tunggu, pesanan akan segera disiapkan.
                        </p>
                    </div>

                    <a href="{{ route('order.index') }}" class="w-full bg-gray-900 text-white font-bold py-4 rounded-2xl shadow-lg flex items-center justify-center hover:bg-gray-800 transition">
                        Kembali ke Menu
                    </a>
                </div>
            </div>
        </div>

        <p class="text-center text-gray-400 text-xs mt-6">RasaNyaman - Sistem Manajemen Restoran v1.0</p>
    </div>

</body>

</html>