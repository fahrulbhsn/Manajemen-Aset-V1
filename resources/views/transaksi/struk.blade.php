<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi - TRX-{{ $transaksi->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; margin: 0; padding: 20px; background-color: #f7f7f7; }
        .container { border: 1px solid #000; padding: 15px; width: 300px; margin: auto; background-color: #fff; }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
        .header h2 { margin: 0; }
        .header p { margin: 0; font-size: 12px; }
        .item { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 12px; }
        .total { display: flex; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #000; font-weight: bold; font-size: 14px;}
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        @media print {
            body { background-color: #fff; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Gigih Computer</h2>
            <p>Jl. Wirotaman, Argopeni, Kutoarjo, Kec. Kutoarjo, Kabupaten Purworejo, Jawa Tengah 54251</p>
        </div>
        <div class="transaction-details">
            <div class="item">
                <span>No. Transaksi:</span>
                <span>TRX-{{ $transaksi->id }}</span>
            </div>
            <div class="item">
                <span>Tanggal:</span>
                <span>{{ \Carbon\Carbon::parse($transaksi->tanggal_jual)->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="item">
                <span>Kasir:</span>
                <span>{{ $transaksi->user->name }}</span>
            </div>
             <div class="item">
                <span>Pembeli:</span>
                <span>{{ $transaksi->nama_pembeli }}</span>
            </div>
        </div>
        <hr>
        <div class="items-list">
            <div class="item">
                <span>{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</span>
                <span>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="total">
            <span>TOTAL</span>
            <span>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</span>
        </div>
        <div class="footer">
            <p>Terima kasih telah berbelanja!</p>
        </div>
    </div>
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Cetak Struk</button>
    </div>
</body>
</html>