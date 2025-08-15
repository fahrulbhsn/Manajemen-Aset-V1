@extends('layouts.admin')

@push('styles')
{{-- CSS responsif --}}
<style>
    @media (max-width: 768px) {
        .table-responsive-stack thead {
            display: none;
        }

        .table-responsive-stack tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
        }

        .table-responsive-stack td {
            display: block;
            text-align: left;
            position: relative;
            padding: 0.75rem 1rem 0.75rem 120px;
            border-bottom: 1px solid #e3e6f0;
            white-space: normal;
        }

        .table-responsive-stack tr:last-child td:last-child,
        .table-responsive-stack td:last-child {
            border-bottom: 0;
        }

        .table-responsive-stack td:before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 110px;
            padding-left: 1rem;
            font-weight: bold;
            text-align: left;
        }
        
        .table-responsive-stack .td-actions {
            padding-left: 1rem;
            text-align: center;
        }
        
        .table-responsive-stack .td-actions:before {
           display: none;
        }
    }
</style>
@endpush

@section('content')

{{-- Judul Halaman --}}
<h1 class="h3 mb-2 text-gray-800">{{ __('Manajemen User') }}</h1>
<p class="mb-4">Daftar semua user yang terdaftar di dalam sistem.</p>

{{-- Konten Utama --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar User</h6>
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
        
        <div class="mb-3">
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                <span class="text">Tambah User</span>
            </a>
        </div>

        {{-- Tabel Data Responsif --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-responsive-stack" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td data-label="Nama">{{ $user->name }}</td>
                            <td data-label="Email">{{ $user->email }}</td>
                            <td data-label="Role">{{ ucfirst($user->role) }}</td>
                            <td data-label="Status" class="text-center">
                                @if($user->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center td-actions">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-circle btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                @if(Auth::user()->id !== $user->id)
                                    <button type="button" class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('users.destroy', $user->id) }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                                @if(Auth::user()->role == 'admin')
                                    @if($user->is_active)
                                        <form action="{{ route('users.deactivate', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-secondary btn-circle btn-sm" title="Non-aktifkan">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('users.activate', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-circle btn-sm" title="Aktifkan">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Data user tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Bagian Paginasi --}}
        <div class="d-flex justify-content-end mt-4">
            {{ $users->links() }}
        </div>
        
    </div>
</div>

@endsection