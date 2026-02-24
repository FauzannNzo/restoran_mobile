<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran #{{ $transaksi->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="text-center">
        <h2 style="margin:0;">RESTO ENAK</h2>
        <p style="margin:5px 0;">Jl. Aminah Syukur No. 82, Samarinda</p>
    </div>

    <div class="line"></div>

    <div class="flex">
        <span>No: #{{ $transaksi->id }}</span>
        <span>{{ $transaksi->created_at->format('d/m/y H:i') }}</span>
    </div>
    <div class="flex">
        <span>Pelanggan:</span>
        <span>{{ $transaksi->nama_konsumen }}</span>
    </div>
    @if($transaksi->meja)
    <div class="flex">
        <span>Meja:</span>
        <span>{{ $transaksi->meja->no_meja }}</span>
    </div>
    @endif
    <div class="flex">
        <span>Kasir:</span>
        <span>{{ $transaksi->user->name ?? 'System' }}</span>
    </div>

    <div class="line"></div>

    @foreach($transaksi->detail as $item)
    <div style="margin-bottom: 5px;">
        <div>{{ $item->menu->nama_menu }}</div>
        <div class="flex">
            <span>{{ $item->jumlah }} x {{ number_format($item->menu->harga, 0, ',', '.') }}</span>
            <span>{{ number_format($item->sub_total, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <div class="line"></div>

    <div class="flex bold" style="font-size: 16px;">
        <span>TOTAL</span>
        <span>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>Bayar ({{ strtoupper($transaksi->metode_pembayaran) }})</span>
        <span>LUNAS</span>
    </div>

    <div class="line"></div>
    <div class="text-center" style="margin-top: 20px;">
        <p>Terima Kasih atas Kunjungan Anda!</p>
        <p>Password Wifi: rasanyaman</p>
    </div>

    <div class="text-center no-print" style="margin-top: 30px;">
        <a href="{{ route('kasir.index') }}" style="text-decoration: none; background: #000; color: #fff; padding: 10px 20px; border-radius: 5px;">Kembali ke Kasir</a>
    </div>

</body>

</html>