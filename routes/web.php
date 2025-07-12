<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
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
    
    Route::resource('kategori', KategoriController::class);
    Route::resource('status', StatusController::class);
    Route::resource('aset', AsetController::class);
    Route::resource('transaksi', TransaksiController::class);

    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/pembelian', [LaporanController::class, 'pembelian'])->name('laporan.pembelian');
    Route::get('/laporan/penjualan/pdf', [LaporanController::class, 'cetak_pdf'])->name('laporan.cetak_pdf');
    Route::get('/laporan/penjualan/excel', [LaporanController::class, 'cetak_excel'])->name('laporan.cetak_excel');
    

    // Rute Khusus Admin
    Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';