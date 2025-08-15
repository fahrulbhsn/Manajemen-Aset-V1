@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Laporan Pembelian</h1>
<p class="mb-4">Ringkasan semua aktivitas pembelian aset yang tercatat dalam sistem.</p>

<!-- Form Filter Tanggal -->
<div class="card shadow mb-4">
     <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        <a href="{{ route('laporan.pembelian') }}" class="btn btn-secondary btn-sm" title="Reset Filter"><i class="fas fa-sync-alt"></i> Refresh</a>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan.pembelian') }}" method="GET">
            <div class="form-row align-items-end">
                <div class="form-group col-md-4">
                    <label for="tanggal_awal">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="tanggal_akhir">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
                 <div class="form-group col-md-2">
                    <a href="{{ route('laporan.pembelian.pdf', request()->query()) }}" class="btn btn-danger btn-block" target="_blank">
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
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Aset Dibeli</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asets->count() }} Unit</div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Rincian Pembelian -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Rincian Pembelian</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-responsive-stack" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Tanggal Beli</th>
                        <th>Harga Beli</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asets as $aset)
                        <tr>
                            <td data-label="No">{{ $loop->iteration }}</td>
                            <td data-label="Nama Aset">{{ $aset->nama_aset }}</td>
                            <td data-label="Kategori">{{ $aset->kategori->name }}</td>
                            <td data-label="Tanggal Beli">{{ \Carbon\Carbon::parse($aset->tanggal_beli)->format('d M Y') }}</td>
                            <td data-label="Harga Beli">Rp {{ number_format($aset->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data pembelian pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection