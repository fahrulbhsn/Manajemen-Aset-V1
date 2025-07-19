<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian Aset</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; font-size: 12px; }
        th { background-color: #f2f2f2; }
        h1, p { text-align: center; }
        h3 { text-align: center; font-weight: normal; }
        .total-row td { font-weight: bold; background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Pembelian Aset</h1>
    @if($tanggal_awal && $tanggal_akhir)
        <p>Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}</p>
    @else
        <p>Periode: Keseluruhan</p>
    @endif

    <hr>
    <h3>Total Pengeluaran: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
    <hr>

    <table>
        <thead>
            <tr>
                <th>Nama Aset</th>
                <th>Kategori</th>
                <th>Tanggal Beli</th>
                <th>Harga Beli</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($asets as $aset)
                <tr>
                    <td>{{ $aset->nama_aset }}</td>
                    <td>{{ $aset->kategori->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($aset->tanggal_beli)->format('d M Y') }}</td>
                    <td>Rp {{ number_format($aset->harga_beli, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data pembelian pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total Pengeluaran</td>
                <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>