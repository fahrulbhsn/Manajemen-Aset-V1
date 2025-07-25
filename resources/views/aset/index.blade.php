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
        
        {{-- Pesan Sukses --}}
         @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif       
        
        {{-- Form Pencarian --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <form action="{{ route('aset.index') }}" method="GET" class="form-inline">
                    @if(request('kategori_id'))
                        <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}">
                    @endif
                    @if(request('status_id'))
                        <input type="hidden" name="status_id" value="{{ request('status_id') }}">
                    @endif
                    @if(request('status_name'))
                        <input type="hidden" name="status_name" value="{{ request('status_name') }}">
                    @endif
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                    @endif
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
                        <th class="text-center">No</th>
                        <th class="text-center">Foto</th>
                        <th style="width:30%;">Nama Aset</th>
                        <th class="text-center">Kategori</th>
                        <th style="width:5%;" class="text-center">
                            <span>Status</span>
                        </th>
                        <th class="text-center">Harga Beli</th>
                        <th class="text-center">Harga Jual</th>
                        <th style="width:8%;" class="text-center">Tanggal Beli</th>
                        <th style="width:10%;" class="text-center">Tanggal Update</th>
                        <th style="width:9%;" class="text-center">
                            <span>Aksi</span></th>
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
                            <td class="text-center">Rp {{ number_format($aset->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-center">Rp {{ number_format($aset->harga_jual, 0, ',', '.') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($aset->tanggal_beli)->format('d-m-Y') }}</td>
                            <td class="text-center">{{ $aset->tanggal_update ? \Carbon\Carbon::parse($aset->tanggal_update)->format('d-m-Y') : '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('aset.show', $aset->id) }}" class="btn btn-info btn-circle btn-sm" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('aset.edit', $aset->id) }}" class="btn btn-warning btn-circle btn-sm ml-2" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-danger btn-circle btn-sm ml-2" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('aset.destroy', $aset->id) }}">
                                    <i class="fas fa-trash"></i>
                                </button>
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