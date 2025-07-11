<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    <p>Periode: {{ $tanggal_awal ?? 'Semua' }} s/d {{ $tanggal_akhir ?? 'Semua' }}</p>
    <hr>
    <h3>Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
    <hr>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Aset</th>
                <th>Tgl Jual</th>
                <th>Harga</th>
                <th>Pembeli</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $transaksi)
            <tr>
                <td>TRX-{{ $transaksi->id }}</td>
                <td>{{ $transaksi->aset->nama_aset ?? '' }}</td>
                <td>{{ $transaksi->tanggal_jual }}</td>
                <td>{{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                <td>{{ $transaksi->nama_pembeli }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>