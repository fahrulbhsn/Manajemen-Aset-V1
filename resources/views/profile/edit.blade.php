@extends('layouts.admin')

@section('content')

{{-- Judul Halaman --}}
<h1 class="h3 mb-4 text-gray-800">{{ __('Profile') }}</h1>

<div class="row">
    <div class="col-lg-8">
        {{-- Update Informasi Profil --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
            </div>
            <div class="card-body">
                {{-- formulir yang sudah disediakan oleh Laravel Breeze --}}
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password --}}
        <div class="card shadow mb-4">
             <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
            </div>
            <div class="card-body">
                {{-- formulir yang sudah disediakan oleh Laravel Breeze --}}
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Hapus Akun --}}
        <div class="card shadow mb-4">
             <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Hapus Akun</h6>
            </div>
            <div class="card-body">
                {{-- Kita memuat formulir yang sudah disediakan oleh Laravel Breeze --}}
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection