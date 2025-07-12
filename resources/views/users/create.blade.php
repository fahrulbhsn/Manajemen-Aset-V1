@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-4 text-gray-800">{{ __('Tambah User Baru') }}</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="role">Peran (Role)</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="editor">Editor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan User</button>
        </form>
    </div>
</div>
@endsection