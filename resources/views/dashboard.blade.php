@extends('layouts.admin')

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="{{ route('aset.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Aset Baru
    </a>
</div>

<div class="row">

    <a href="{{ route('aset.index', ['status_name' => 'Tersedia']) }}" class="col-xl-4 col-md-6 mb-4 text-decoration-none">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Aset Tersedia</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asetTersedia }} Unit</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-archive fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('aset.index', ['status_name' => 'Terjual']) }}" class="col-xl-4 col-md-6 mb-4 text-decoration-none">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aset Terjual</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asetTerjual }} Unit</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </a>

    <a href="{{ route('aset.index', ['status_name' => 'Perbaikan']) }}" class="col-xl-4 col-md-6 mb-4 text-decoration-none">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Dalam Perbaikan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asetPerbaikan }} Unit</div>
                    </div>
                    <div class="col-auto"><i class="fas fa-tools fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </a>
</div>

<div class="row">

    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aktivitas Transaksi Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <tbody>
                        @forelse ($transaksiTerbaru as $transaksi)
                            <tr>
                                <td>
                                    <strong>{{ $transaksi->aset->nama_aset ?? 'Aset Dihapus' }}</strong><br>
                                    <small>dijual kepada {{ $transaksi->nama_pembeli }} oleh {{ $transaksi->user->name }}</small>
                                </td>
                                <td class="text-right">
                                    <small>{{ $transaksi->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>Belum ada transaksi terbaru.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('transaksi.index') }}">Lihat Semua Transaksi &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script>
// Logika untuk menggambar grafik
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: {!! json_encode($labels) !!}, // Mengambil data tanggal dari controller
    datasets: [{
      label: "Jumlah Penjualan",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: {!! json_encode($data) !!}, // Mengambil data jumlah penjualan dari controller
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
    scales: {
        xAxes: [{ time: { unit: 'date' }, gridLines: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } }],
        yAxes: [{ ticks: { maxTicksLimit: 5, padding: 10, callback: function(value) { if (Number.isInteger(value)) { return value; } } }, gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] } }],
    },
    legend: { display: false },
    tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        intersect: false,
        mode: 'index',
        caretPadding: 10,
    }
  }
});
</script>
@endpush
