@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Laporan Laba Rugi</h1>
<p class="mb-4">Ringkasan keuntungan bersih dari semua aktivitas penjualan.</p>

<!-- Form Filter Tanggal -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        <a href="{{ route('laporan.laba_rugi') }}" class="btn btn-secondary btn-sm" title="Reset Filter"><i class="fas fa-sync-alt"></i> Refresh</a>
    </div>
    <div class="card-body">
        <form action="{{ route('laporan.laba_rugi') }}" method="GET">
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
                    <a href="{{ route('laporan.laba_rugi.pdf', request()->query()) }}" class="btn btn-danger btn-block" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Kartu Ringkasan -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendapatan</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Modal (Harga Beli)</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalModal, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Laba Bersih</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($labaBersih, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Rincian -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Rincian Perhitungan Laba per Transaksi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-responsive-stack">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Aset Terjual</th>
                        <th>Harga Jual</th>
                        <th>Harga Beli (Modal)</th>
                        <th>Laba</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td data-label="No">{{ $loop->iteration }}</td>
                            <td data-label="Aset Terjual">{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                            <td data-label="Harga Jual">Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                            <td data-label="Harga Beli (Modal)">Rp {{ number_format($transaksi->aset->harga_beli ?? 0, 0, ',', '.') }}</td>
                            <td data-label="Laba">Rp {{ number_format(($transaksi->harga_jual_akhir) - ($transaksi->aset->harga_beli ?? 0), 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection