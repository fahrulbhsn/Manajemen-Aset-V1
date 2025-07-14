@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Laporan Pembelian</h1>
<p class="mb-4">Ringkasan semua aktivitas pembelian aset yang tercatat dalam sistem.</p>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.pembelian') }}" method="GET">
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
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
                <a href="{{ route('laporan.pembelian.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> Ekspor PDF
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Total Pengeluaran</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Aset Dibeli</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asets->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-archive fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Rincian Pembelian</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                            <td colspan="4" class="text-center">Belum ada data pembelian pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection