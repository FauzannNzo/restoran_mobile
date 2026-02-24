@extends('layouts.app')

@section('title', 'Kasir Area')
@section('header', 'Kasir')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <h3 class="font-bold text-gray-700 text-lg mb-4 flex items-center gap-2">
                <i class="ph ph-clock-countdown text-blue-600"></i> Menunggu Pembayaran
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="p-3">Info Pesanan</th>
                            <th class="p-3">Metode</th>
                            <th class="p-3">Total</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @forelse($transaksis as $trx)
                        <tr class="border-b last:border-0 hover:bg-gray-50">
                            <td class="p-3">
                                <div class="font-bold">{{ $trx->nama_konsumen }}</div>
                                <div class="text-xs text-gray-500">
                                    @if($trx->meja) Meja {{ $trx->meja->no_meja }} @else Take Away @endif
                                    • {{ $trx->created_at->format('d M Y H:i') }}
                                </div>
                            </td>
                            <td class="p-3">
                                @if($trx->metode_pembayaran == 'qris')
                                <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-bold uppercase">QRIS</span>
                                @else
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold uppercase">CASH</span>
                                @endif
                            </td>
                            <td class="p-3 font-mono font-bold text-blue-600">
                                Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center">
                                <button onclick="openPaymentModal({{ $trx->id }}, '{{ $trx->nama_konsumen }}', {{ $trx->total_bayar }}, '{{ $trx->metode_pembayaran }}')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">
                                    Proses
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-400 text-sm">Tidak ada tagihan pending.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-gray-700 text-lg mb-4 flex items-center gap-2">
                <i class="ph ph-receipt text-green-600"></i> Riwayat Hari Ini
            </h3>
            <div class="overflow-x-auto max-h-60 overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase sticky top-0">
                        <tr>
                            <th class="p-3">ID & Nama</th>
                            <th class="p-3">Total</th>
                            <th class="p-3">Metode</th>
                            <th class="p-3 text-center">Struk</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @forelse($completedTransaksis as $done)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">
                                <span class="font-mono text-xs text-gray-400">#{{ $done->id }}</span>
                                <div class="font-bold">{{ $done->nama_konsumen }}</div>
                            </td>
                            <td class="p-3">Rp {{ number_format($done->total_bayar, 0, ',', '.') }}</td>
                            <td class="p-3 uppercase text-xs font-bold text-gray-500">{{ $done->metode_pembayaran }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('kasir.print', $done->id) }}" target="_blank" class="text-gray-400 hover:text-gray-800">
                                    <i class="ph ph-printer text-xl"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-400 text-sm">Belum ada transaksi selesai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 h-fit sticky top-24">
        <h3 class="font-bold text-gray-700 text-lg mb-4 flex items-center gap-2">
            <i class="ph ph-chair text-purple-600"></i> Manajemen Meja
        </h3>
        <div class="grid grid-cols-2 gap-3">
            @foreach($mejas as $meja)
            <div class="border rounded-lg p-3 text-center relative group {{ $meja->status == 'booking' ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200' }}">
                <span class="block text-2xl font-bold {{ $meja->status == 'booking' ? 'text-red-600' : 'text-green-600' }}">
                    {{ $meja->no_meja }}
                </span>
                <span class="text-[10px] uppercase font-bold {{ $meja->status == 'booking' ? 'text-red-400' : 'text-green-400' }}">
                    {{ $meja->status }}
                </span>
                <div class="absolute inset-0 bg-white/90 hidden group-hover:flex flex-col justify-center items-center gap-2 p-1 rounded-lg transition-all">
                    @if($meja->status == 'booking')
                    <form action="{{ route('kasir.updateMeja', $meja->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="tersedia">
                        <button type="submit" class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded font-bold hover:bg-green-200 w-full">Set Kosong</button>
                    </form>
                    @else
                    <form action="{{ route('kasir.updateMeja', $meja->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="booking">
                        <button type="submit" class="text-[10px] bg-red-100 text-red-700 px-2 py-1 rounded font-bold hover:bg-red-200 w-full">Set Isi</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div id="paymentModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl transform transition-all scale-100">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Pembayaran</h3>
            <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600"><i class="ph-bold ph-x text-xl"></i></button>
        </div>

        <p class="text-sm text-gray-500 mb-2">Pelanggan: <span id="modalName" class="font-bold text-gray-800"></span></p>

        <div class="bg-blue-50 p-4 rounded-xl mb-4 flex justify-between items-center border border-blue-100">
            <span class="text-xs text-blue-500 uppercase font-bold">Total Tagihan</span>
            <span class="text-2xl font-bold text-blue-700">Rp <span id="modalTotalDisplay">0</span></span>
        </div>

        <form id="paymentForm" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-3">
                    <label onclick="toggleCalculator('cash')" class="border rounded-lg p-3 flex items-center justify-center gap-2 cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input type="radio" id="radioCash" name="metode_pembayaran" value="cash" class="hidden">
                        <i class="ph ph-money text-xl"></i> <span>Tunai</span>
                    </label>
                    <label onclick="toggleCalculator('qris')" class="border rounded-lg p-3 flex items-center justify-center gap-2 cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                        <input type="radio" id="radioQris" name="metode_pembayaran" value="qris" class="hidden">
                        <i class="ph ph-qr-code text-xl"></i> <span>QRIS</span>
                    </label>
                </div>
            </div>

            <div id="calculatorSection" class="mb-6 space-y-3 transition-all duration-300">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Uang Diterima (Rp)</label>
                    <input type="number" id="inputBayar" onkeyup="hitungKembalian()" class="w-full text-lg font-bold border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2" placeholder="Masukkan nominal...">
                </div>

                <div class="flex justify-between items-center pt-2 border-t border-dashed">
                    <span class="text-sm font-bold text-gray-500">Kembalian</span>
                    <span class="text-xl font-bold text-green-600">Rp <span id="textKembalian">0</span></span>
                </div>
            </div>

            <div id="qrisInfoSection" class="mb-6 hidden text-center bg-purple-50 p-4 rounded-xl border border-purple-100">
                <i class="ph ph-check-circle text-3xl text-purple-600 mb-1"></i>
                <p class="text-sm font-bold text-purple-700">Pembayaran QRIS</p>
                <p class="text-xs text-purple-500">Pastikan bukti transfer valid & sesuai nominal.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg flex justify-center items-center gap-2">
                <i class="ph-bold ph-printer"></i> Konfirmasi & Cetak
            </button>
        </form>
    </div>
</div>

<script>
    let currentTotal = 0;

    function openPaymentModal(id, name, total, method) {
        currentTotal = total;
        document.getElementById('modalName').innerText = name;
        document.getElementById('modalTotalDisplay').innerText = new Intl.NumberFormat('id-ID').format(total);

        // Reset Input
        document.getElementById('inputBayar').value = '';
        document.getElementById('textKembalian').innerText = '0';

        // Set Action URL
        let url = "{{ route('kasir.confirm', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('paymentForm').action = url;

        // --- LOGIC AUTO SELECT METODE ---
        if (method === 'qris') {
            document.getElementById('radioQris').checked = true;
            toggleCalculator('qris');
        } else {
            document.getElementById('radioCash').checked = true;
            toggleCalculator('cash');
        }

        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function toggleCalculator(type) {
        const calc = document.getElementById('calculatorSection');
        const qrisInfo = document.getElementById('qrisInfoSection');
        const inputBayar = document.getElementById('inputBayar');

        if (type === 'qris') {
            // Sembunyikan Kalkulator
            calc.classList.add('hidden');
            qrisInfo.classList.remove('hidden');
            inputBayar.removeAttribute('required'); // Tidak wajib isi uang
        } else {
            // Tampilkan Kalkulator
            calc.classList.remove('hidden');
            qrisInfo.classList.add('hidden');
            inputBayar.setAttribute('required', 'required'); // Wajib isi uang
            inputBayar.focus();
        }
    }

    function hitungKembalian() {
        let bayar = document.getElementById('inputBayar').value;
        let kembalian = bayar - currentTotal;

        if (kembalian < 0) {
            document.getElementById('textKembalian').innerText = '-';
            document.getElementById('textKembalian').classList.add('text-red-500');
            document.getElementById('textKembalian').classList.remove('text-green-600');
        } else {
            document.getElementById('textKembalian').innerText = new Intl.NumberFormat('id-ID').format(kembalian);
            document.getElementById('textKembalian').classList.remove('text-red-500');
            document.getElementById('textKembalian').classList.add('text-green-600');
        }
    }
    setTimeout(function() {
        window.location.reload(1);
    }, 15000);
</script>

@endsection