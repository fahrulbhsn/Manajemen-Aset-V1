<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController; // Import ActivityLogController
use App\Models\Aset;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Halaman Awal (Tidak Perlu Diubah)
Route::get('/', function () {
    return view('welcome');
});

// Rute Dashboard (HANYA SATU DEFINISI YANG BENAR)
Route::get('/dashboard', function () {
    // Hitung jumlah aset untuk setiap status
    $asetTersedia = Aset::whereHas('status', function($q){ $q->where('name', 'Tersedia'); })->count();
    $asetTerjual = Aset::whereHas('status', function($q){ $q->where('name', 'Terjual'); })->count();
    $asetPerbaikan = Aset::whereHas('status', function($q){ $q->where('name', 'Perbaikan'); })->count();

    // Kirim semua data hitungan ke view dashboard
    return view('dashboard', compact('asetTersedia', 'asetTerjual', 'asetPerbaikan'));

})->middleware(['auth', 'verified'])->name('dashboard');


// Rute-rute Lain yang Membutuhkan Login
    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  
        // Rute khusus untuk pencarian aset via AJAX
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
    Route::get('/laporan/penjualan/excel', [LaporanController::class, 'cetak_excel'])->name('laporan.cetak_excel');
    // Rute untuk cetak struk transaksi
    Route::get('/transaksi/{transaksi}/cetak', [App\Http\Controllers\TransaksiController::class, 'cetak_struk'])->name('transaksi.cetak_struk');
    Route::resource('transaksi', App\Http\Controllers\TransaksiController::class);
    

    // Rute Khusus Admin
    Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/aktivitas', [ActivityLogController::class, 'index'])->name('aktivitas.index');
    });
});

require __DIR__.'/auth.php';