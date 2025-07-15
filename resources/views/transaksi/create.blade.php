@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-4 text-gray-800">Tambah Transaksi Baru</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf

            {{-- Bagian Pilih Aset --}}
            <div class="form-group">
                <label for="aset_id">Pilih Aset yang akan Dijual</label>
                {{-- Ajax search --}}
                <select id="select-aset" class="form-control" name="aset_id" required>
                    {{-- Biarkan kosong, akan diisi oleh AJAX --}}
                </select>
                {{-- Tambahkan pesan error validasi jika ada --}}
                @error('aset_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <hr>

            {{-- Bagian Informasi Pembeli --}}
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nama_pembeli">Nama Pembeli</label>
                    <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" required>
                    {{-- Tambahkan pesan error validasi jika ada --}}
                    @error('nama_pembeli')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="kontak_pembeli">Kontak Pembeli (No. HP/Email)</label>
                    <input type="text" class="form-control" id="kontak_pembeli" name="kontak_pembeli" required>
                    {{-- Tambahkan pesan error validasi jika ada --}}
                    @error('kontak_pembeli')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div> {{-- Tutup form-row untuk Informasi Pembeli --}}

            {{-- Bagian Metode Pembayaran --}}
            <div class="form-group">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" class="form-control" name="metode_pembayaran" required>
                    <option value="" selected disabled>Pilih Metode...</option>
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="QRIS">QRIS</option>
                </select>
                {{-- Tambahkan pesan error validasi jika ada --}}
                @error('metode_pembayaran')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Bagian Detail Penjualan --}}
            {{-- Perbaikan: Hanya satu form-row di sini --}}
            <div class="form-row"> 
                <div class="form-group col-md-6">
                    <label for="harga_jual_akhir">Harga Jual Akhir (Setelah Nego)</label>
                    <input type="number" class="form-control" id="harga_jual_akhir" name="harga_jual_akhir" required>
                    {{-- Tambahkan pesan error validasi jika ada --}}
                    @error('harga_jual_akhir')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="tanggal_jual">Tanggal Jual</label>
                    <input type="date" class="form-control" id="tanggal_jual" name="tanggal_jual" value="{{ date('Y-m-d') }}" required>
                    {{-- Tambahkan pesan error validasi jika ada --}}
                    @error('tanggal_jual')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div> {{-- Tutup form-row untuk Detail Penjualan --}}
            
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#select-aset').select2({
            placeholder: 'Ketik nama aset...',
            ajax: {
                url: "{{ route('aset.search') }}",
                dataType: 'json',
                delay: 250, // Jeda 250 milidetik sebelum mengirim request
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 3 // Minimal 3 karakter baru mencari
        });
    });
</script>
@endpush

@push('scripts')
<script>
    // Menambahkan event listener pada form saat disubmit
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const submitButton = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function () {
            // Saat tombol diklik, nonaktifkan tombolnya
            submitButton.disabled = true;
            // Ubah teksnya untuk memberikan feedback ke pengguna
            submitButton.innerHTML = 'Menyimpan...';
        });
    });
</script>
@endpush