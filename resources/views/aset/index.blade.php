@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">{{ __('Manajemen Aset') }}</h1>
<p class="mb-4">Daftar semua aset yang tercatat di dalam sistem.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('aset.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Tambah Aset Baru</span>
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
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Tgl Beli</th>
                        <th>Tgl Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asets as $aset)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($aset->foto)
                                    <img src="{{ asset('foto_aset/' . $aset->foto) }}" alt="{{ $aset->nama_aset }}" width="100">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $aset->nama_aset }}</td>
                            <td>{{ $aset->kategori->name }}</td>
                            <td>{{ $aset->status->name }}</td>
                            <td>Rp {{ number_format($aset->harga_beli, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($aset->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($aset->tanggal_beli)->format('d-m-Y') }}</td>
                            <td>{{ $aset->tanggal_update ? \Carbon\Carbon::parse($aset->tanggal_update)->format('d-m-Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('aset.show', $aset->id) }}" class="btn btn-info btn-circle btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('aset.edit', $aset->id) }}" class="btn btn-warning btn-circle btn-sm">
                                    <i class="fas fa-edit"></i> </a>
                                <form action="{{ route('aset.destroy', $aset->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus aset ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-circle btn-sm">
                                        <i class="fas fa-trash"></i> </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Belum ada data aset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection