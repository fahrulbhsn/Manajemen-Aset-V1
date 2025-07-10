@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-4 text-gray-800">{{ __('Tambah Aset Baru') }}</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('aset.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nama_aset">Nama Aset</label>
                    <input type="text" class="form-control" id="nama_aset" name="nama_aset" value="{{ old('nama_aset') }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="kategori_id">Kategori</label>
                    <select id="kategori_id" class="form-control" name="kategori_id" required>
                        <option selected disabled>Pilih Kategori...</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="harga_beli">Harga Beli</label>
                    <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="harga_jual">Harga Jual</label>
                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="{{ old('harga_jual') }}" required>
                </div>
            </div>
            <div class="form-row">
                 <div class="form-group col-md-6">
                    <label for="tanggal_beli">Tanggal Beli</label>
                    <input type="date" class="form-control" id="tanggal_beli" name="tanggal_beli" value="{{ old('tanggal_beli') }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="status_id">Status</label>
                    <select id="status_id" class="form-control" name="status_id" required>
                        <option selected disabled>Pilih Status...</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="detail">Detail Spesifikasi</label>
                <textarea class="form-control" id="detail" name="detail" rows="3">{{ old('detail') }}</textarea>
            </div>

            <div class="form-group">
                <label for="foto">Foto Aset</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Aset</button>
        </form>
    </div>
</div>
@endsection