@extends('layouts.admin')

@section('content')

{{-- Judul Halaman --}}
<h1 class="h3 mb-2 text-gray-800">Manajemen Aset</h1>
<p class="mb-4">Daftar semua aset yang tercatat dalam sistem.</p>

{{-- Konten Utama --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Aset</h6>
        <div>
            {{-- Tombol Refresh --}}
            <a href="{{ route('aset.index') }}" class="btn btn-secondary btn-sm" title="Reset Tampilan">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
            <a href="{{ route('aset.create') }}" class="btn btn-primary btn-sm ml-2">
                <i class="fas fa-plus"></i> Tambah Aset Baru
            </a>
        </div>
    </div>
    <div class="card-body">

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif

        {{-- Form Opsi dan Pencarian --}}
        <div class="row mb-3">
            <div class="col-md-8">
                <form action="{{ route('aset.index') }}" method="GET" class="form-inline">
                    <label for="per_page" class="mr-2">Tampilkan:</label>
                    <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                        <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
            <div class="col-md-4">
                <form action="{{ route('aset.index') }}" method="GET">
                    {{-- Input Tersembunyi untuk Menyimpan Jumlah Data per Halaman --}}
                    <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama aset..." value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Data Aset --}}
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Tanggal Beli</th>
                        <th>Tanggal Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asets as $aset)
                        <tr>
                            <td>{{ ($asets->currentPage() - 1) * $asets->perPage() + $loop->iteration }}</td>
                            <td>
                                @if($aset->foto)
                                    <img src="{{ asset('foto_aset/' . $aset->foto) }}" alt="{{ $aset->nama_aset }}" width="100">
                                @else
                                    <span class="text-muted">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td>{{ $aset->nama_aset }}</td>
                            <td>{{ $aset->kategori->name }}</td>
                            <td>
                                @if($aset->status->name == 'Tersedia')
                                    <span class="badge badge-success">{{ $aset->status->name }}</span>
                                @elseif($aset->status->name == 'Perbaikan')
                                    <span class="badge badge-warning">{{ $aset->status->name }}</span>
                                @else
                                    {{-- Untuk status 'Terjual' atau status lainnya --}}
                                    <span class="badge badge-danger">{{ $aset->status->name }}</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($aset->harga_beli, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($aset->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($aset->tanggal_beli)->format('d-m-Y') }}</td>
                            <td>{{ $aset->tanggal_update ? \Carbon\Carbon::parse($aset->tanggal_update)->format('d-m-Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('aset.show', $aset->id) }}" class="btn btn-info btn-circle btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('aset.edit', $aset->id) }}" class="btn btn-warning btn-circle btn-sm ml-2" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('aset.destroy', $aset->id) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Anda yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-circle btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        <div class="d-flex justify-content-end mt-4">
            {{ $asets->withQueryString()->links() }}
        </div>
    </div>
</div>

@endsection