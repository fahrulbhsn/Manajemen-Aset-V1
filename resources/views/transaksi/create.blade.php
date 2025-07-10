@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-4 text-gray-800">Tambah Transaksi Baru</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="aset_id">Pilih Aset yang akan Dijual</label>
                <select id="aset_id" class="form-control" name="aset_id" required>
                    <option selected disabled>Pilih Aset...</option>
                    @foreach($asets as $aset)
                        <option value="{{ $aset->id }}">
                            {{ $aset->nama_aset }} (Harga: Rp {{ number_format($aset->harga_jual, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>
            <hr>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nama_pembeli">Nama Pembeli</label>
                    <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="kontak_pembeli">Kontak Pembeli (No. HP/Email)</label>
                    <input type="text" class="form-control" id="kontak_pembeli" name="kontak_pembeli" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="harga_jual_akhir">Harga Jual Akhir (Setelah Nego)</label>
                    <input type="number" class="form-control" id="harga_jual_akhir" name="harga_jual_akhir" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="tanggal_jual">Tanggal Jual</label>
                    <input type="date" class="form-control" id="tanggal_jual" name="tanggal_jual" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>
</div>
@endsection