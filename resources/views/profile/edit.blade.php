@extends('layouts.admin')

@section('content')

{{-- Judul Halaman --}}
<h1 class="h3 mb-4 text-gray-800">{{ __('Profil Pengguna') }}</h1>

<div class="row">
    <div class="col-lg-8">
        {{-- Update Informasi Profil --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>
@endsection