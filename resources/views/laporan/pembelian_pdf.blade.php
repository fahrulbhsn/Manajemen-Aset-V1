<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian Aset</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        h1, h3 { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Pembelian Aset</h1>
    @if($tanggal_awal && $tanggal_akhir)
        <h3>Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}</h3>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nama Aset</th>
                <th>Kategori</th>
                <th>Harga Beli</th>
                <th>Tanggal Beli</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($asets as $aset)
                <tr>
                    <td>{{ $aset->nama_aset }}</td>
                    <td>{{ $aset->kategori->name }}</td>
                    <td>Rp {{ number_format($aset->harga_beli, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($aset->tanggal_beli)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>