<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Models\Aset;
use App\Models\Transaksi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

    // Rute Halaman Awal
    Route::get('/', function () {
        return view('welcome');
    });

    // Rute-rute yang Membutuhkan Login
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            $asetTersedia = Aset::whereHas('status', function($q){ $q->where('name', 'Tersedia'); })->count();
            $asetTerjual = Aset::whereHas('status', function($q){ $q->where('name', 'Terjual'); })->count();
            $asetPerbaikan = Aset::whereHas('status', function($q){ $q->where('name', 'Perbaikan'); })->count();
            $transaksiTerbaru = Transaksi::with(['aset', 'user'])->latest()->take(5)->get();
            $penjualanSeminggu = Transaksi::where('tanggal_jual', '>=', now()->subDays(7))
                ->selectRaw('DATE(tanggal_jual) as tanggal, COUNT(*) as jumlah')
                ->groupBy('tanggal')->orderBy('tanggal', 'asc')->get();
            $labels = $penjualanSeminggu->pluck('tanggal')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'));
            $data = $penjualanSeminggu->pluck('jumlah');
            return view('dashboard', compact('asetTersedia', 'asetTerjual', 'asetPerbaikan', 'transaksiTerbaru', 'labels', 'data'));})->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        // Rute pencarian aset via AJAX
        Route::get('/api/aset/search', [AsetController::class, 'search'])->name('aset.search');
        Route::resource('kategori', KategoriController::class);
        Route::resource('status', StatusController::class);
        Route::resource('aset', AsetController::class);
        Route::resource('transaksi', TransaksiController::class);
        // Rute untuk laporan
        Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/laporan/pembelian', [LaporanController::class, 'pembelian'])->name('laporan.pembelian');
        Route::get('/laporan/laba-rugi', [LaporanController::class, 'laba_rugi'])->name('laporan.laba_rugi');
        Route::get('/laporan/penjualan/cetak-pdf', [LaporanController::class, 'cetak_penjualan_pdf'])->name('laporan.penjualan.pdf');
        Route::get('/laporan/pembelian/pdf', [LaporanController::class, 'cetak_pembelian'])->name('laporan.pembelian.pdf');
        Route::get('/laporan/laba-rugi/pdf', [LaporanController::class, 'cetak_laba_rugi'])->name('laporan.laba_rugi.pdf');
        // Rute untuk cetak struk transaksi
        Route::get('/transaksi/{transaksi}/cetak', [App\Http\Controllers\TransaksiController::class, 'cetak_struk'])->name('transaksi.cetak_struk');
        Route::resource('transaksi', App\Http\Controllers\TransaksiController::class);

    // Rute Khusus Admin
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/aktivitas', [ActivityLogController::class, 'index'])->name('aktivitas.index');
        Route::put('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
        Route::put('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    });
});

require __DIR__.'/auth.php';