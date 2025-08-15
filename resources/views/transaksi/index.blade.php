@extends('layouts.admin')

@push('styles')
{{-- Style untuk tabel responsif di layar kecil (disamakan dengan Aset) --}}
<style>
    /* CSS untuk membuat tabel menjadi responsif (stacking) pada layar kecil */
    @media (max-width: 768px) {
        .table-responsive-stack thead {
            display: none; /* Sembunyikan header tabel di mobile */
        }

        .table-responsive-stack tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e3e6f0; /* Tambahkan border untuk setiap "kartu" */
        }

        .table-responsive-stack td {
            display: block;
            text-align: right; /* Posisikan data di kanan */
            border: none;
            border-bottom: 1px solid #e3e6f0;
            position: relative;
            padding-left: 50%; /* Beri ruang untuk label */
            white-space: normal;
        }

        .table-responsive-stack td:last-child {
            border-bottom: 0;
        }

        /* Buat label dari atribut data-label */
        .table-responsive-stack td:before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 45%;
            padding-left: 1rem;
            font-weight: bold;
            text-align: left; /* Posisikan label di kiri */
        }
        
        /* Penyesuaian khusus untuk kolom aksi */
        .td-actions {
            text-align: center !important; /* Pusatkan tombol aksi */
            padding-left: 1rem !important; /* Hapus padding kiri agar tombol di tengah */
        }
    }
</style>
@endpush

@section('content')

{{-- Judul Halaman --}}
<h1 class="h3 mb-2 text-gray-800">Daftar Transaksi</h1>
<p class="mb-4">Riwayat semua transaksi penjualan yang telah tercatat dalam sistem.</p>

{{-- Konten Utama --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
    </div>
    <div class="card-body">
        
        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif      
        
        {{-- Filter, Pencarian, dan Tombol Aksi (STRUKTUR BARU SESUAI ASET) --}}
        <div class="mb-3">
            <form action="{{ route('transaksi.index') }}" method="GET">
                <input type="hidden" name="tanggal_awal" value="{{ $tanggal_awal ?? '' }}">
                <input type="hidden" name="tanggal_akhir" value="{{ $tanggal_akhir ?? '' }}">
                
                <div class="row align-items-center">
                    {{-- Tombol Aksi Kiri --}}
                    <div class="col-12 col-md-auto mb-2 mb-md-0">
                        <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                            <span class="text">Tambah Transaksi</span>
                        </a>
                        <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-circle" title="Reset Filter">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>

                    {{-- Filter & Pencarian Kanan --}}
                    <div class="col-12 col-md-auto ml-md-auto">
                        <div class="d-flex justify-content-end">
                            {{-- Filter Jumlah Data --}}
                            <div class="form-group mb-0 mr-2" style="width: 100px;">
                                <select name="per_page" class="form-control" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>

                            {{-- Input Pencarian --}}
                            <div class="input-group" style="max-width: 300px;">
                                <input type="text" name="search" class="form-control" placeholder="Cari ID, Aset, Pembeli..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-responsive-stack" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Aset Terjual</th>
                        <th>Tgl Jual</th>
                        <th class="text-right">Harga Akhir</th>
                        <th>Pembeli</th>
                        <th>Metode Bayar</th>
                        <th>Dicatat oleh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td data-label="ID Transaksi">TRX-{{ $transaksi->id }}</td>
                            <td data-label="Aset Terjual">{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                            <td data-label="Tgl Jual">{{ \Carbon\Carbon::parse($transaksi->tanggal_jual)->format('d M Y') }}</td>
                            <td data-label="Harga Akhir" class="text-right">Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                            <td data-label="Pembeli">{{ $transaksi->nama_pembeli }}</td>
                            <td data-label="Metode Bayar">{{ $transaksi->metode_pembayaran }}</td>
                            <td data-label="Dicatat oleh">{{ $transaksi->user->name }}</td>
                            <td class="text-center td-actions">
                                <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-info btn-circle btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('transaksi.destroy', $transaksi->id) }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Bagian Paginasi --}}
        <div class="d-flex justify-content-end mt-4">
            {{ $transaksis->withQueryString()->links() }}
        </div>

    </div>
</div>

@endsection