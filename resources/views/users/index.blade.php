@extends('layouts.admin')

@push('styles')
{{-- CSS tabel responsif--}}
<style>
    @media (max-width: 768px) {
        .table-responsive-stack thead {
            display: none; /* Sembunyikan header di mobile */
        }
        .table-responsive-stack tr {
            display: block;
            margin-bottom: 1.5rem;
            border: 1px solid #e3e6f0;
            border-radius: .35rem; /* Efek kartu */
            box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
        }
        .table-responsive-stack td {
            display: block;
            text-align: right;
            border: none;
            border-bottom: 1px solid #e3e6f0;
            position: relative;
            padding: .75rem 1rem .75rem 50%;
            white-space: normal;
        }
        .table-responsive-stack td:first-child {
            border-top-left-radius: .35rem;
            border-top-right-radius: .35rem;
        }
        .table-responsive-stack td:last-child {
            border-bottom: 0;
        }
        .table-responsive-stack td:before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 45%;
            padding-left: 1rem;
            font-weight: bold;
            text-align: left;
        }
        .td-actions {
            text-align: center !important; /* Pusatkan tombol aksi */
            padding-left: 1rem !important;
        }
    }
</style>
@endpush

@section('content')
    <h1 class="h3 mb-2 text-gray-800">Manajemen User</h1>
    <p class="mb-4">Daftar semua akun pengguna yang terdaftar di dalam sistem.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                <span class="text">Tambah User Baru</span>
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                {{-- PENYESUAIAN: Menambahkan kelas 'table-hover' dan 'table-responsive-stack' --}}
                <table class="table table-bordered table-hover table-responsive-stack" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th class="text-center">Peran</th>
                            <th class="text-center">Status Akun</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                {{-- PENYESUAIAN: Menambahkan atribut data-label --}}
                                <td data-label="Nama">{{ $user->name }}</td>
                                <td data-label="Email">{{ $user->email }}</td>
                                <td data-label="Peran" class="text-center">
                                    @if($user->role == 'admin')
                                        <span class="badge badge-success">{{ ucfirst($user->role) }}</span>
                                    @else
                                        <span class="badge badge-info">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>
                                <td data-label="Status Akun" class="text-center">
                                    @if($user->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td data-label="Aksi" class="text-center td-actions">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-circle btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->is_active)
                                        <form action="{{ route('users.deactivate', $user->id) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Anda yakin ingin menonaktifkan user ini?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-secondary btn-circle btn-sm" title="Nonaktifkan User">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('users.activate', $user->id) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Anda yakin ingin mengaktifkan user ini?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-circle btn-sm" title="Aktifkan User">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-danger btn-circle btn-sm ml-2" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('users.destroy', $user->id) }}" title="Hapus Permanen">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data pengguna lain.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection