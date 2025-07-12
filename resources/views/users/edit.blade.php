@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-4 text-gray-800">{{ __('Edit User') }}</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="role">Peran (Role)</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="editor" {{ $user->role == 'editor' ? 'selected' : '' }}>Editor</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <hr>
            <p class="text-muted">Isi bagian password hanya jika Anda ingin mengubahnya.</p>

            <div class="form-group">
                <label for="password">Password Baru (Opsional)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
            
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</div>
@endsection