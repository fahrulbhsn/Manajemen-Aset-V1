@extends('layouts.admin')

@push('styles')
{{-- CSS tabel responsif--}}
<style>
    @media (max-width: 768px) {
        .table-responsive-stack thead {
            display: none;
        }

        .table-responsive-stack tr {
            display: block;
            margin-bottom: 1.5rem;
            border: 1px solid #e3e6f0;
            border-radius: .35rem;
        }
        
        .table-responsive-stack td {
            display: block;
            text-align: right;
            border: none;
            border-bottom: 1px solid #e3e6f0;
            position: relative;
            padding: 0.75rem 1.25rem;
            padding-left: 50%; 
            white-space: normal;
        }

        .table-responsive-stack td:first-child {
            border-top-left-radius: .35rem;
            border-top-right-radius: .35rem;
        }
        .table-responsive-stack td:last-child {
            border-bottom: 0;
        }

        .table-responsive-stack td:before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 45%;
            padding-left: 1.25rem;
            font-weight: bold;
            text-align: left;
            word-wrap: break-word;
        }

        .td-actions {
            text-align: center !important;
            padding: 1rem !important;
            display: flex; 
            justify-content: center;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')

<h1 class="h3 mb-2 text-gray-800">Log Aktivitas Pengguna</h1>
<p class="mb-4">Riwayat semua aktivitas penting yang tercatat dalam sistem.</p>

{{-- Kartu untuk Filter --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter & Opsi Tampilan</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('aktivitas.index') }}" method="GET" class="filter-form">
            <div class="row align-items-end">
                <div class="form-group col-md-4 col-lg-3">
                    <label for="per_page">Tampilkan per Halaman:</label>
                    <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                        <option value="20" {{ ($perPage ?? 20) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ ($perPage ?? 20) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ ($perPage ?? 20) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="form-group col-md-4 col-lg-3">
                    <label for="tanggal_awal">Dari Tanggal:</label>
                    <input type="date" name="tanggal_awal" class="form-control" value="{{ $tanggal_awal ?? '' }}">
                </div>
                <div class="form-group col-md-4 col-lg-3">
                    <label for="tanggal_akhir">Sampai Tanggal:</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggal_akhir ?? '' }}">
                </div>
                <div class="form-group col-md-12 col-lg-3 mt-3 mt-lg-0">
                    <div class="d-flex flex-column flex-md-row justify-content-start">
                        <button class="btn btn-primary btn-icon-split mb-2 mb-md-0" type="submit">
                            <span class="text">Filter</span>
                        </button>
                        <a href="{{ route('aktivitas.index') }}" class="btn btn-secondary btn-icon-split ml-md-2" title="Reset Filter">
                            <span class="text">Refresh</span>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Kartu untuk Tabel Aktivitas --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Aktivitas</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-responsive-stack" id="dataTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 20%;">Waktu</th>
                        <th style="width: 15%;">Pengguna</th>
                        <th class="text-center" style="width: 15%;">Aksi</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td data-label="Waktu">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                            <td data-label="Pengguna">{{ $log->user->name ?? 'Pengguna Dihapus' }}</td>
                            <td data-label="Aksi" class="text-center">
                                @if($log->action == 'menambah' || $log->action == 'menyetujui')
                                    <span class="badge badge-success">{{ ucfirst($log->action) }}</span>
                                @elseif($log->action == 'mengubah')
                                    <span class="badge badge-warning">{{ ucfirst($log->action) }}</span>
                                @elseif($log->action == 'menghapus' || $log->action == 'menolak')
                                    <span class="badge badge-danger">{{ ucfirst($log->action) }}</span>
                                @else
                                    <span class="badge badge-info">{{ ucfirst($log->action) }}</span>
                                @endif
                            </td>
                            <td data-label="Deskripsi">{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada aktivitas yang sesuai dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-4">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>

@endsection