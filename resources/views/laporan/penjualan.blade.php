@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Laporan Penjualan</h1>
<p class="mb-4">Ringkasan semua aktivitas penjualan yang tercatat dalam sistem.</p>

<!-- Form Filter Tanggal -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        <a href="{{ route('laporan.penjualan') }}" class="btn btn-secondary btn-sm" title="Reset Filter"><i class="fas fa-sync-alt"></i> Refresh</a>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan.penjualan') }}" method="GET">
            <div class="form-row align-items-end">
                <div class="form-group col-md-4">
                    <label for="tanggal_awal">Tanggal Awal</label>
                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggal_awal ?? '' }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggal_akhir ?? '' }}">
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
                <div class="form-group col-md-2">
                    <a href="{{ route('laporan.penjualan.pdf', request()->query()) }}" class="btn btn-danger btn-block" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Kartu Ringkasan -->
<div class="row">
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Aset Terjual</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transaksis->count() }} Unit</div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Rincian Penjualan -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Rincian Penjualan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-responsive-stack" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Aset Terjual</th>
                        <th>Tanggal Jual</th>
                        <th>Harga Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td data-label="No">{{ $loop->iteration }}</td>
                            <td data-label="ID Transaksi">TRX-{{ $transaksi->id }}</td>
                            <td data-label="Aset Terjual">{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                            <td data-label="Tanggal Jual">{{ \Carbon\Carbon::parse($transaksi->tanggal_jual)->format('d M Y') }}</td>
                            <td data-label="Harga Akhir">Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data penjualan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection