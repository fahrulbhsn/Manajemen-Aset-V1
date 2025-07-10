@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Daftar Transaksi</h1>
<p class="mb-4">Riwayat semua transaksi penjualan yang telah tercatat dalam sistem.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Tambah Transaksi Baru</span>
        </a>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Aset Terjual</th>
                        <th>Tanggal Jual</th>
                        <th>Harga Akhir</th>
                        <th>Nama Pembeli</th>
                        <th>Dicatat oleh</th>
                        <th>Aksi</th>
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
                            <td>
                                {{-- Tombol Detail/Cetak Struk akan kita tambahkan nanti --}}
                                <a href="#" class="btn btn-info btn-circle btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection