@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">{{ __('Manajemen Kategori') }}</h1>
<p class="mb-4">Daftar semua kategori aset yang tersedia di dalam sistem.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-icon-split">
            <span class="text">Tambah Kategori Baru</span>
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
                        <th>Nama Kategori</th>
                        <th>Stok Tersedia</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoris as $kategori)
                        <tr>
                            <td>{{ $kategori->name }}</td>
                            {{-- Menambahkan tautan interaktif pada Stok Tersedia untuk memfilter aset --}}
                            <td>
                                <a href="{{ route('aset.index', ['kategori_id' => $kategori->id, 'status_name' => 'Tersedia']) }}">
                                    {{ $kategori->asets_count }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning btn-circle btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Anda yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-circle btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection