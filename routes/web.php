<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('kategori', KategoriController::class);
    Route::resource('status', StatusController::class);
    Route::resource('aset', AsetController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/penjualan/pdf', [LaporanController::class, 'cetak_pdf'])->name('laporan.cetak_pdf');
    Route::get('/laporan/penjualan/excel', [LaporanController::class, 'cetak_excel'])->name('laporan.cetak_excel');
    // Rute Khusus Admin
    Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';
