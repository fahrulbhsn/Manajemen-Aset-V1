@extends('layouts.admin')

@push('styles')
{{-- CSS responsif --}}
<style>
    @media (max-width: 768px) {
        .table-responsive-stack thead {
            display: none;
        }

        .table-responsive-stack tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
        }

        .table-responsive-stack td {
            display: flex;
            align-items: baseline;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e3e6f0;
            white-space: normal;
        }

        .table-responsive-stack td:before {
            content: attr(data-label);
            font-weight: bold;
            text-align: left;
            flex-basis: 100px;
            flex-shrink: 0;
            margin-right: 1rem;
        }

        .table-responsive-stack tr:last-child td:last-child,
        .table-responsive-stack td:last-child {
            border-bottom: 0;
        }
        
        .table-responsive-stack .td-actions {
            justify-content: center;
        }
        
        .table-responsive-stack .td-actions:before {
           display: none;
        }
    }
</style>
@endpush

@section('content')

<h1 class="h3 mb-2 text-gray-800">Pusat Persetujuan</h1>
<p class="mb-4">Daftar permintaan perubahan atau penghapusan data yang memerlukan persetujuan Anda.</p>

{{-- Pesan Notifikasi --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

{{-- Tabel Persetujuan Aset --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Persetujuan Aset</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-responsive-stack" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>Nama Aset</th>
                        <th class="text-center">Jenis Permintaan</th>
                        <th>Detail Perubahan</th>
                        <th class="text-center" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($asetsToApprove as $aset)
                        <tr>
                            <td data-label="Nama Aset">{{ $aset->nama_aset }}</td>
                            <td data-label="Jenis Permintaan" class="text-center">
                                @if($aset->approval_status == 'menunggu persetujuan edit')
                                    <span class="badge badge-warning">Edit</span>
                                @elseif($aset->approval_status == 'menunggu persetujuan hapus')
                                    <span class="badge badge-danger">Hapus</span>
                                @endif
                            </td>
                            <td data-label="Detail Perubahan" class="details-column">
                                @if($aset->approval_status == 'menunggu persetujuan edit' && $aset->pending_data)
                                    @php $pendingData = json_decode($aset->pending_data, true); @endphp
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($pendingData as $key => $value)
                                        <li class="change-item">
                                            <strong class="change-label">
                                                @if($key == 'kategori_id') Kategori
                                                @elseif($key == 'status_id') Status
                                                @else {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                @endif
                                            </strong>
                                            <span class="change-value text-danger">
                                                Dari: 
                                                @if($key == 'kategori_id') {{ $aset->kategori->name ?? 'N/A' }}
                                                @elseif($key == 'status_id') {{ $aset->status->name ?? 'N/A' }}
                                                @else {{ $aset->$key }}
                                                @endif
                                            </span>
                                            <span class="change-value text-success">
                                                Menjadi: 
                                                @if($key == 'kategori_id') {{ $kategoris[$value] ?? '-' }}
                                                @elseif($key == 'status_id') {{ $statuses[$value] ?? '-' }}
                                                @else {{ is_array($value) ? implode(', ', $value) : $value }}
                                                @endif
                                            </span>
                                        </li>
                                        @endforeach
                                    </ul>
                                @else
                                    Data aset ini akan dihapus permanen.
                                @endif
                            </td>
                            <td data-label="Aksi" class="text-center">
                                <form action="{{ route('approval.aset.approve', $aset->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm mb-1" title="Setujui"><i class="fas fa-check"></i> Setujui</button>
                                </form>
                                <form action="{{ route('approval.aset.reject', $aset->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Tolak"><i class="fas fa-times"></i> Tolak</button>
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

{{-- Tabel Persetujuan Transaksi --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Persetujuan Transaksi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-responsive-stack" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">ID Transaksi</th>
                        <th class="text-center">Jenis Permintaan</th>
                        <th>Detail Perubahan</th>
                        <th class="text-center" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksisToApprove as $transaksi)
                        <tr>
                            <td data-label="ID Transaksi" class="text-center">TRX-{{ $transaksi->id }}</td>
                            <td data-label="Jenis Permintaan" class="text-center">
                                @if($transaksi->approval_status == 'menunggu persetujuan edit')
                                    <span class="badge badge-warning">Edit</span>
                                @elseif($transaksi->approval_status == 'menunggu persetujuan hapus')
                                    <span class="badge badge-danger">Hapus</span>
                                @endif
                            </td>
                            <td data-label="Detail Perubahan" class="details-column">
                                @if($transaksi->approval_status == 'menunggu persetujuan edit' && $transaksi->pending_data)
                                    @php $pendingData = json_decode($transaksi->pending_data, true); @endphp
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach($pendingData as $key => $value)
                                        <li class="change-item">
                                            <strong class="change-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</strong>
                                            <span class="change-value text-danger">Dari: {{ $transaksi->$key }}</span>
                                            <span class="change-value text-success">Menjadi: {{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                @else
                                    Transaksi ini akan dihapus permanen.
                                @endif
                            </td>
                            <td data-label="Aksi" class="text-center">
                                <form action="{{ route('approval.transaksi.approve', $transaksi->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm mb-1" title="Setujui"><i class="fas fa-check"></i> Setujui</button>
                                </form>
                                <form action="{{ route('approval.transaksi.reject', $transaksi->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Tolak"><i class="fas fa-times"></i> Tolak</button>
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