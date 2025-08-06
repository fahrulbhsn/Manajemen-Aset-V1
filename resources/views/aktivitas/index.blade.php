@extends('layouts.admin')

@section('content')

<h1 class="h3 mb-2 text-gray-800">Log Aktivitas Pengguna</h1>
<p class="mb-4">Riwayat semua aktivitas penting yang tercatat dalam sistem.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Filter & Opsi Tampilan</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('aktivitas.index') }}" method="GET">
            <div class="form-row align-items-end">
                <div class="form-group col-md-4">
                    <label for="per_page">Tampilkan per Halaman:</label>
                    {{-- 'onchange="this.form.submit()"' akan otomatis refresh halaman --}}
                    <select name="per_page" id="per_page" class="form-control" onchange="this.form.submit()">
                        <option value="20" {{ ($perPage ?? 20) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ ($perPage ?? 20) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ ($perPage ?? 20) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="tanggal_awal">Dari Tanggal:</label>
                    <input type="date" name="tanggal_awal" class="form-control" value="{{ $tanggal_awal ?? '' }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="tanggal_akhir">Sampai Tanggal:</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ $tanggal_akhir ?? '' }}">
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-primary btn-block" type="submit">Filter</button>
                </div>
                <div class="form-group col-md-1">
                    <a href="{{ route('aktivitas.index') }}" class="btn btn-secondary btn-block" title="Reset Filter">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Aktivitas</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 20%;">Waktu</th>
                        <th style="width: 15%;">Pengguna</th>
                        <th style="width: 15%;">Aksi</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                            <td>{{ $log->user->name ?? 'Pengguna Dihapus' }}</td>
                                <td>
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
                            <td>{{ $log->description }}</td>
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