@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Manajemen User</h1>
<p class="mb-4">Daftar semua akun pengguna yang terdaftar di dalam sistem.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        {{-- Tombol Tambah User akan kita buat nanti --}}
        <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah User Baru</span>
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran (Role)</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge badge-success">{{ $user->role }}</span>
                                @else
                                    <span class="badge badge-info">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td>
                                {{-- Tombol Aksi untuk Edit dan Hapus User --}}
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-circle btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline ml-2" onsubmit="return confirm('Anda yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-circle btn-sm ml-2" data-toggle="modal" data-target="#deleteModal" data-url="{{ route('users.destroy', $user->id) }}" title="Hapus User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data pengguna lain.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection