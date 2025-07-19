<!DOCTYPE html>
<html>
<head>
    <title>Laporan Laba Rugi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; font-size: 12px;}
        th { background-color: #f2f2f2; }
        h1, p { text-align: center; }
        h3 { text-align: center; font-weight: normal; }
        .total-row td { font-weight: bold; background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Laba Rugi</h1>
    @if($tanggal_awal && $tanggal_akhir)
        <p>Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}</p>
    @else
        <p>Periode: Keseluruhan</p>
    @endif

    <hr>
    <h3>Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
    <h3>Total Modal: Rp {{ number_format($totalModal, 0, ',', '.') }}</h3>
    <h3>Laba Bersih: Rp {{ number_format($labaBersih, 0, ',', '.') }}</h3>
    <hr>

    <table>
        <thead>
            <tr>
                <th>Aset Terjual</th>
                <th>Harga Jual</th>
                <th>Harga Beli (Modal)</th>
                <th>Laba</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $transaksi)
                <tr>
                    <td>{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                    <td>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($transaksi->aset->harga_beli ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format(($transaksi->harga_jual_akhir) - ($transaksi->aset->harga_beli ?? 0), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td style="text-align: right;">Total</td>
                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($labaBersih, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>