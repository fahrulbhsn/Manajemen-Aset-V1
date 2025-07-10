@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Daftar Transaksi</h1>
<p class="mb-4">Riwayat semua transaksi penjualan yang telah tercatat.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary">Tambah Transaksi Baru</a>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Aset Terjual</th>
                        <th>Tgl Jual</th>
                        <th>Harga Akhir</th>
                        <th>Pembeli</th>
                        <th>Dicatat oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td>{{ $transaksi->aset->nama_aset }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_jual)->format('d-m-Y') }}</td>
                            <td>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                            <td>{{ $transaksi->nama_pembeli }}</td>
                            <td>{{ $transaksi->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection