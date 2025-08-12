@extends('layouts.admin')

@section('content')

{{-- Judul Halaman --}}
<h1 class="h3 mb-2 text-gray-800">Daftar Transaksi</h1>
<p class="mb-4">Riwayat semua transaksi penjualan yang telah tercatat dalam sistem.</p>

{{-- Tabel transaksi --}}
<div class="card shadow mb-4">
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
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{--FORM FILTER DAN PENCARIAN YANG DISEMPURNAKAN --}}
        <div class="row mb-3">
            {{-- Form untuk Opsi Tampilan --}}
            <div class="col-md-8">
                <form action="{{ route('transaksi.index') }}" method="GET" class="form-inline">
                    {{-- Input tersembunyi untuk membawa filter lain yang aktif --}}
                    <input type="hidden" name="search" value="{{ $search ?? '' }}">
                    <input type="hidden" name="tanggal_awal" value="{{ $tanggal_awal ?? '' }}">
                    <input type="hidden" name="tanggal_akhir" value="{{ $tanggal_akhir ?? '' }}">

                    <label for="per_page" class="mr-2">Tampilkan:</label>
                    <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                        <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
            {{-- Form untuk Pencarian dan Filter Tanggal --}}
            <div class="col-md-4">
                 <form action="{{ route('transaksi.index') }}" method="GET">
                    <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari ID, Aset, Pembeli, Kasir..." value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Aset Terjual</th>
                        <th>Tgl Jual</th>
                        <th>Harga Akhir</th>
                        <th>Pembeli</th>
                        <th>Metode Bayar</th>
                        <th>Dicatat oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                        <tr>
                            <td>{{ ($transaksis->currentPage() - 1) * $transaksis->perPage() + $loop->iteration }}</td>
                            <td>TRX-{{ $transaksi->id }}</td>
                            <td>{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_jual)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                            <td>{{ $transaksi->nama_pembeli }}</td>
                            <td>{{ $transaksi->metode_pembayaran }}</td>
                            <td>{{ $transaksi->user->name }}</td>
                            <td>
                                <a href="{{ route('transaksi.show', $transaksi->id) }}" class="btn btn-info btn-circle btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-warning btn-circle btn-sm ml-2" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-danger btn-circle btn-sm ml-2" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('transaksi.destroy', $transaksi->id) }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Data tidak ditemukan.</td>
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