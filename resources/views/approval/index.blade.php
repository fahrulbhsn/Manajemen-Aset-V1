@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Pusat Persetujuan</h1>
<p class="mb-4">Daftar semua permintaan perubahan atau penghapusan data yang memerlukan persetujuan Anda.</p>

{{-- Pesan Notifikasi --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Persetujuan Aset</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Aset</th>
                        <th>Jenis Permintaan</th>
                        <th>Detail Perubahan</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asetsToApprove as $aset)
                        <tr>
                            <td>{{ $aset->nama_aset }}</td>
                            <td>
                                @if($aset->approval_status == 'menunggu persetujuan edit')
                                    <span class="badge badge-warning">Edit</span>
                                @elseif($aset->approval_status == 'menunggu persetujuan hapus')
                                    <span class="badge badge-danger">Hapus</span>
                                @endif
                            </td>
                            <td>
                                @if($aset->approval_status == 'menunggu persetujuan edit' && $aset->pending_data)
                                    @php $pendingData = json_decode($aset->pending_data, true); @endphp
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($pendingData as $key => $value)
                                            <li>
                                                {{-- Mengganti nama kunci agar lebih mudah dibaca --}}
                                                <strong>
                                                    @if($key == 'kategori_id') Kategori
                                                    @elseif($key == 'status_id') Status
                                                    @else {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                    @endif
                                                </strong>
                                                <br>
                                                <span class="text-danger">Dari: 
                                                    @if($key == 'kategori_id') {{ $aset->kategori->name ?? 'N/A' }}
                                                    @elseif($key == 'status_id') {{ $aset->status->name ?? 'N/A' }}
                                                    @else {{ $aset->$key }}
                                                    @endif
                                                </span>
                                                <br>
                                                <span class="text-success">Menjadi: 
                                                    {{-- Mencari nama dari ID untuk Kategori dan Status --}}
                                                    @if($key == 'kategori_id')
                                                        {{ $kategoris[$value] ?? '-' }}
                                                    @elseif($key == 'status_id')
                                                        {{ $statuses[$value] ?? '-' }}
                                                    @else
                                                        {{-- PERBAIKAN: Periksa apakah nilainya array --}}
                                                        @if(is_array($value))
                                                            {{-- Jika array, gabungkan menjadi string --}}
                                                            {{ implode(', ', $value) }}
                                                        @else
                                                            {{-- Jika bukan array, tampilkan seperti biasa --}}
                                                            {{ $value }}
                                                        @endif
                                                    @endif
                                                </span>
                                            </li>
                                            <hr class="my-1">
                                        @endforeach
                                    </ul>
                                @else
                                    Data aset ini akan dihapus permanen.
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('approval.aset.approve', $aset->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form action="{{ route('approval.aset.reject', $aset->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Tidak ada permintaan persetujuan untuk Aset.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Persetujuan Transaksi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Jenis Permintaan</th>
                        <th>Detail Perubahan</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksisToApprove as $transaksi)
                        <tr>
                            <td>TRX-{{ $transaksi->id }}</td>
                            <td>
                                @if($transaksi->approval_status == 'menunggu persetujuan edit')
                                    <span class="badge badge-warning">Edit</span>
                                @elseif($transaksi->approval_status == 'menunggu persetujuan hapus')
                                    <span class="badge badge-danger">Hapus</span>
                                @endif
                            </td>
                            <td>
                                @if($transaksi->approval_status == 'menunggu persetujuan edit' && $transaksi->pending_data)
                                    @php $pendingData = json_decode($transaksi->pending_data, true); @endphp
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($pendingData as $key => $value)
                                            <li>
                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                <br>
                                                <span class="text-danger">Dari: {{ $transaksi->$key }}</span>
                                                <br>
                                                <span class="text-success">Menjadi: 
                                                    {{-- PERBAIKAN: Periksa apakah nilainya array --}}
                                                    @if(is_array($value))
                                                        {{-- Jika array, gabungkan menjadi string --}}
                                                        {{ implode(', ', $value) }}
                                                    @else
                                                        {{-- Jika bukan array, tampilkan seperti biasa --}}
                                                        {{ $value }}
                                                    @endif
                                                </span>
                                            </li>
                                             <hr class="my-1">
                                        @endforeach
                                    </ul>
                                @else
                                    Transaksi ini akan dihapus permanen.
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('approval.transaksi.approve', $transaksi->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form action="{{ route('approval.transaksi.reject', $transaksi->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Tidak ada permintaan persetujuan untuk Transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection