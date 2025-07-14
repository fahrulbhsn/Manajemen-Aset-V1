<!DOCTYPE html>
<html>
<head>
    <title>Laporan Laba Rugi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        h1, h3 { text-align: center; }
        .total-row td { font-weight: bold; background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Laba Rugi</h1>
    @if($tanggal_awal && $tanggal_akhir)
        <h3>Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}</h3>
    @endif

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
            @foreach ($transaksis as $transaksi)
                <tr>
                    <td>{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                    <td>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($transaksi->aset->harga_beli ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format(($transaksi->harga_jual_akhir) - ($transaksi->aset->harga_beli ?? 0), 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="1">Total</td>
                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($labaBersih, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>