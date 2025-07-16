@extends('layouts.admin')

{{-- Bagian konten utama --}}
@section('content')

{{-- Judul Halaman --}}
<h1 class="h3 mb-2 text-gray-800">Daftar Transaksi</h1>
{{-- Deskripsi Halaman --}}
<p class="mb-4">Riwayat semua transaksi penjualan yang telah tercatat dalam sistem.</p>

{{-- Kartu untuk tabel transaksi --}}
<div class="card shadow mb-4">
    {{-- Header kartu dengan tombol tambah transaksi --}}
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
    <div>
        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm" title="Reset Tampilan">
            <i class="fas fa-sync-alt"></i> Refresh
        </a>
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm ml-2">
            <i class="fas fa-plus"></i> Tambah Transaksi Baru
        </a>
    </div>
</div>
    {{-- Body kartu berisi alert dan tabel --}}
    <div class="card-body">
        {{-- Menampilkan pesan sukses jika ada --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <form action="{{ route('transaksi.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari ID, Aset, atau Kasir..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal_awal" class="form-control" value="{{ $tanggal_awal ?? '' }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggal_akhir ?? '' }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit">Filter</button>
                </div>
            </div>
        </form>
        {{-- Area responsif untuk tabel --}}
        <div class="table-responsive">
            {{-- Tabel data transaksi --}}
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Aset Terjual</th>
                        <th>Tanggal Jual</th>
                        <th>Harga Akhir</th>
                        <th>Nama Pembeli</th>
                        <th>Metode Pembayaran</th>
                        <th>Dicatat oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop melalui setiap transaksi --}}
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td>TRX-{{ $transaksi->id }}</td>
                            {{-- Menampilkan nama aset atau 'Aset Dihapus' jika aset tidak ada --}}
                            <td>{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                            {{-- Memformat tanggal jual --}}
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_jual)->format('d M Y') }}</td>
                            {{-- Memformat harga jual akhir ke format mata uang Rupiah --}}
                            <td>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                            <td>{{ $transaksi->nama_pembeli }}</td>
                            <td>{{ $transaksi->metode_pembayaran }}</td>
                            <td>{{ $transaksi->user->name }}</td>
                            <td>
                                {{-- Tombol detail transaksi --}}
                                <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-info btn-circle btn-sm" title="Detail Transaksi">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Tombol edit transaksi --}}
                                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-warning btn-circle btn-sm ml-2" title="Edit Transaksi">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- Form untuk menghapus transaksi --}}
                                <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Anda yakin ingin menghapus transaksi ini? Status aset akan dikembalikan menjadi Tersedia.');">
                                    @csrf {{-- Token CSRF untuk keamanan --}}
                                    @method('DELETE') {{-- Metode HTTP DELETE --}}
                                    <button type="submit" class="btn btn-danger btn-circle btn-sm" title="Hapus Transaksi">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- Pesan jika tidak ada data transaksi --}}
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
