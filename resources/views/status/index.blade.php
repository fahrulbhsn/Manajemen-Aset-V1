@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">{{ __('Manajemen Status') }}</h1>
<p class="mb-4">Daftar semua status aset yang tersedia di dalam sistem.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        @if(Auth::user()->role == 'admin')
        <a href="{{ route('status.create') }}" class="btn btn-primary">Tambah Status Baru</a>
        @endif
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Status</th>
                        <th>Total Stok</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($statuses as $status)
                        <tr>
                            <td>{{ $status->name }}</td>
                            <td>{{ $status->asets_count }}</td>
                            <td>
                                <a href="{{ route('aset.index', ['status_id' => $status->id]) }}" class="btn btn-info btn-circle btn-sm" title="Lihat Aset dengan status ini">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(Auth::user()->role == 'admin')
                                <a href="{{ route('status.edit', $status->id) }}" class="btn btn-warning btn-circle btn-sm ml-2" title="Edit Status">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('status.destroy', $status->id) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Anda yakin?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-circle btn-sm ml-2" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('status.destroy', $status->id) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada data status.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection