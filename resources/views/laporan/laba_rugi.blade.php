@extends('layouts.admin') {{-- Menggunakan layout 'admin' sebagai template dasar --}}

@section('content') {{-- Mendefinisikan bagian konten untuk layout --}}

    {{-- Judul Halaman dan Deskripsi --}}
    <h1 class="h3 mb-2 text-gray-800">Laporan Laba Rugi</h1>
    <p class="mb-4">Ringkasan keuntungan bersih dari semua aktivitas penjualan.</p>

    {{-- Form Filter Tanggal --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('laporan.laba_rugi') }}" method="GET">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-5">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                    </div>
                    <div class="form-group col-md-5">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Laba Rugi (Cards) --}}
    <div class="row">
        {{-- Card Total Pendapatan --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendapatan</div>
                    {{-- Menampilkan total pendapatan dengan format mata uang --}}
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        {{-- Card Total Modal (Harga Beli) --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Modal (Harga Beli)</div>
                    {{-- Menampilkan total modal dengan format mata uang --}}
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalModal, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        {{-- Card Laba Bersih --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Laba Bersih</div>
                    {{-- Menampilkan laba bersih dengan format mata uang --}}
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($labaBersih, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rincian Perhitungan Laba per Transaksi (Tabel) --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rincian Perhitungan Laba per Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Aset Terjual</th>
                            <th>Harga Jual</th>
                            <th>Harga Beli (Modal)</th>
                            <th>Laba</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Melakukan loop untuk setiap transaksi --}}
                        @forelse ($transaksis as $transaksi)
                            <tr>
                                {{-- Menampilkan nama aset, jika aset dihapus akan tampil 'Aset Dihapus' --}}
                                <td>{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</td>
                                {{-- Menampilkan harga jual akhir dengan format mata uang --}}
                                <td>Rp {{ number_format($transaksi->harga_jual_akhir, 0, ',', '.') }}</td>
                                {{-- Menampilkan harga beli aset, jika tidak ada akan tampil 0 --}}
                                <td>Rp {{ number_format($transaksi->aset->harga_beli ?? 0, 0, ',', '.') }}</td>
                                {{-- Menampilkan laba per transaksi (harga jual - harga beli) --}}
                                <td>Rp {{ number_format(($transaksi->harga_jual_akhir) - ($transaksi->aset->harga_beli ?? 0), 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            {{-- Pesan jika tidak ada data transaksi --}}
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data transaksi pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection {{-- Mengakhiri bagian konten --}}