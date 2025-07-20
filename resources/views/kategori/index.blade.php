@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">{{ __('Manajemen Kategori') }}</h1>
<p class="mb-4">Daftar semua kategori aset yang tersedia di dalam sistem.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah Kategori Baru</a>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Stok Tersedia</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategoris as $kategori)
                        <tr>
                            <td>{{ $kategori->name }}</td>
                            <td>{{ $kategori->asets->where('status.name', 'Tersedia')->count() }}</td>
                            <td>
                                {{-- PERBAIKAN LINK FILTER ADA DI SINI --}}
                                <a href="{{ route('aset.index', ['kategori_id' => $kategori->id, 'status_name' => 'Tersedia']) }}" class="btn btn-info btn-circle btn-sm" title="Lihat Aset Tersedia">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-warning btn-circle btn-sm ml-2" title="Edit Kategori">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-circle btn-sm ml-2" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('kategori.destroy', $kategori->id) }}">
                                    <i class="fas fa-trash"></i>
                                </button>
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