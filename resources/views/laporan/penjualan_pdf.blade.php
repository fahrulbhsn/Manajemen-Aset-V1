<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; font-size: 12px;}
        th { background-color: #f2f2f2; }
        h1, h3 { text-align: center; }
        p { text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <h1>Laporan Penjualan</h1>
    @if($tanggal_awal && $tanggal_akhir)
        <p>Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}</p>
    @else
        <p>Periode: Keseluruhan</p>
    @endif

    <hr>
    <h3>Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
    <hr>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Aset Terjual</th>
                <th>Tanggal Jual</th>
                <th>Harga Akhir</th>
                <th>Pembeli</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $transaksi)
                <tr>
                    <td>TRX-{{ $transaksi->id }}</td>
                    <td>{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_jual)->format('d-m-Y') }}</td>
                    <td>{{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                    <td>{{ $transaksi->nama_pembeli }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>