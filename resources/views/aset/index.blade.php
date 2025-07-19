@extends('layouts.admin')

@push('styles')
{{-- Style khusus untuk halaman ini (saat ini dikomentari untuk ukuran gambar normal) --}}
{{--
<style>
    .table-fixed-layout {
        table-layout: fixed;
        word-wrap: break-word;
    }
    .table-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
</style>
--}}
@endpush

@section('content')

{{-- Judul Halaman --}}
<h1 class="h3 mb-2 text-gray-800">{{ __('Manajemen Aset') }}</h1>
<p class="mb-4">Daftar semua aset yang tercatat di dalam sistem.</p>

{{-- Konten Utama --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Aset</h6>
        <div>
            <a href="{{ route('aset.index') }}" class="btn btn-secondary btn-sm" title="Reset Urutan">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
            <a href="{{ route('aset.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Aset Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        
        {{-- Form Pencarian --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <form action="{{ route('aset.index') }}" method="GET" class="form-inline">
                    <label for="per_page" class="mr-2">Tampilkan:</label>
                    <select name="per_page" id="per_page" class="form-control mr-2" onchange="this.form.submit()">
                        <option value="10" {{ ($per_page ?? 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ ($per_page ?? 10) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ ($per_page ?? 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ ($per_page ?? 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <input type="hidden" name="search" value="{{ $search ?? '' }}">
                    <input type="hidden" name="sort" value="{{ $sort ?? '' }}">
                    <input type="hidden" name="direction" value="{{ $direction ?? '' }}">
                </form>
            </div>
            <div class="col-md-8">
                <form action="{{ route('aset.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Nama Aset, Kategori, atau Status..." value="{{ $search ?? '' }}">
                        <input type="hidden" name="per_page" value="{{ $per_page ?? 10 }}">
                        <input type="hidden" name="sort" value="{{ $sort ?? '' }}">
                        <input type="hidden" name="direction" value="{{ $direction ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th style="width:30%;">Nama Aset</th>
                        <th>Kategori</th>
                        <th style="width:5%;" class="text-center">
                            <span>Status</span>
                        </th>
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
                            <td>{{ $loop->iteration }}</td> {{-- Menggunakan nomor urut biasa --}}
                            <td>
                                @if($aset->foto)
                                    <img src="{{ asset('foto_aset/' . $aset->foto) }}" alt="{{ $aset->nama_aset }}" width="100">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $aset->nama_aset }}</td>
                            <td>{{ $aset->kategori->name }}</td>
                            <td class="text-center">
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
                                <form action="{{ route('aset.destroy', $aset->id) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Anda yakin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-circle btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- Colspan tetap 10 karena semua kolom kembali ada --}}
                            <td colspan="10" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Bagian Paginasi --}}
        <div class="d-flex justify-content-end mt-4">
            {{ $asets->withQueryString()->links() }}
        </div>
        
    </div>
</div>

@endsection

@push('scripts')
{{-- Kita bisa menambahkan skrip khusus untuk halaman ini jika diperlukan nanti --}}
@endpush
