@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Detail Transaksi</h1>
<p class="mb-4">Informasi lengkap untuk transaksi <strong>TRX-{{ $transaksi->id }}</strong></p>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Rincian Transaksi</h6>
        <div>
            <a href="{{ route('transaksi.cetak_struk', $transaksi->id) }}" class="btn btn-success btn-sm" target="_blank">
                <i class="fas fa-print"></i> Cetak Struk
            </a>
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm ml-2">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Detail Penjualan</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">ID Transaksi</th>
                        <td>TRX-{{ $transaksi->id }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Jual</th>
                        <td>{{ $transaksi->tanggal_jual ? \Carbon\Carbon::parse($transaksi->tanggal_jual)->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Harga Jual Akhir</th>
                        <td>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>{{ $transaksi->metode_pembayaran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dicatat oleh</th>
                        <td>{{ $transaksi->user->name ?? 'Pengguna Tidak Ditemukan' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Detail Pembeli & Aset</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Nama Pembeli</th>
                        <td>{{ $transaksi->nama_pembeli ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Kontak Pembeli</th>
                        <td>{{ $transaksi->kontak_pembeli ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Aset yang Dibeli</th>
                        <td>{{ $transaksi->aset->nama_aset ?? 'Aset Telah Dihapus' }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Aset</th>
                        {{-- Menggunakan null coalescing operator untuk nama kategori --}}
                        <td>{{ $transaksi->aset->kategori->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection