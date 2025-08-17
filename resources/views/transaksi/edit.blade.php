@extends('layouts.admin')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Edit Transaksi (TRX-{{ $transaksi->id }})</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Aset: {{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</h6>    
        <div>
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nama_pembeli">Nama Pembeli</label>
                    <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" value="{{ old('nama_pembeli', $transaksi->nama_pembeli) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="kontak_pembeli">Kontak Pembeli</label>
                    <input type="text" class="form-control" id="kontak_pembeli" name="kontak_pembeli" value="{{ old('kontak_pembeli', $transaksi->kontak_pembeli) }}" required>
                </div>
            </div>
             <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" class="form-control" name="metode_pembayaran" required>
                    <option value="Tunai" {{ $transaksi->metode_pembayaran == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="Transfer Bank" {{ $transaksi->metode_pembayaran == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="QRIS" {{ $transaksi->metode_pembayaran == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="harga_jual_akhir">Harga Jual Akhir</label>
                    <input type="number" class="form-control" id="harga_jual_akhir" name="harga_jual_akhir" value="{{ old('harga_jual_akhir', $transaksi->harga_jual_akhir) }}" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="tanggal_jual">Tanggal Jual</label>
                    <input type="date" class="form-control" id="tanggal_jual" name="tanggal_jual" value="{{ old('tanggal_jual', $transaksi->tanggal_jual) }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Transaksi</button>
        </form>
    </div>
</div>
@endsection