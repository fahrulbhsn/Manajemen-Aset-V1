@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Laporan Penjualan</h1>
<p class="mb-4">Ringkasan semua aktivitas penjualan yang tercatat dalam sistem.</p>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.penjualan') }}" method="GET">
            <div class="form-row align-items-end">
                <div class="form-group col-md-5">
                    <label for="tanggal_awal">Tanggal Awal</label>
                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                </div>
                <div class="form-group col-md-5">
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="form-group col-md-2">
                        <button type="submit" class="btn btn-primary" style="width: 48%;">Filter</button>
                        <a href="{{ route('laporan.penjualan') }}" class="btn btn-secondary" style="width: 48%;">Refresh</a>
                </div>
                <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn btn-danger mr-2" target="_blank">
                    <i class="fas fa-file-pdf"></i> Ekspor PDF
                </a>
                <a href="{{ route('laporan.penjualan.excel', request()->query()) }}" class="btn btn-success mr-2">
                    <i class="fas fa-file-excel"></i> Ekspor Excel
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pendapatan (Keseluruhan)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Transaksi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transaksis->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Rincian Seluruh Transaksi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Aset Terjual</th>
                        <th>Tanggal Jual</th>
                        <th>Harga Akhir</th>
                        <th>Pembeli</th>
                        <th>Dicatat oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td>TRX-{{ $transaksi->id }}</td>
                            <td>{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_jual)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                            <td>{{ $transaksi->nama_pembeli }}</td>
                            <td>{{ $transaksi->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection