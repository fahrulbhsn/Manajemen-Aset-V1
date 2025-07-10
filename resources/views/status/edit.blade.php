@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-4 text-gray-800">{{ __('Edit Status') }}</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Status</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('status.update', $status->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Status</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $status->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>

@endsection