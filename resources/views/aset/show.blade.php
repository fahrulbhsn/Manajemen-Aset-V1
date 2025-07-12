@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Detail Aset</h1>
<p class="mb-4">Informasi lengkap untuk aset: <strong>{{ $aset->nama_aset }}</strong></p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('aset.index') }}" class="btn btn-secondary btn-sm">Kembali ke Daftar Aset</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                @if($aset->foto)
                    <img src="{{ asset('foto_aset/' . $aset->foto) }}" class="img-fluid rounded" alt="{{ $aset->nama_aset }}">
                @else
                    <div class="text-center p-5 bg-light">
                        <span class="text-muted">Tidak Ada Gambar</span>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <h3>{{ $aset->nama_aset }}</h3>
                <table class="table table-bordered mt-3">
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $aset->kategori->name }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $aset->status->name }}</td>
                    </tr>
                    <tr>
                        <th>Harga Beli</th>
                        <td>Rp {{ number_format($aset->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td>Rp {{ number_format($aset->harga_jual, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Beli</th>
                        <td>{{ \Carbon\Carbon::parse($aset->tanggal_beli)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Update Terakhir</th>
                        <td>{{ $aset->tanggal_update ? \Carbon\Carbon::parse($aset->tanggal_update)->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Detail Spesifikasi</th>
                        <td>{{ $aset->detail ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection