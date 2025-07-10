<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan.
     */
    public function penjualan(Request $request)
    {
        // Ambil semua data transaksi untuk dihitung dan ditampilkan
        $transaksis = Transaksi::latest()->get();

        // Hitung total pendapatan dari semua transaksi
        $totalPendapatan = $transaksis->sum('harga_jual_akhir');

        // Kirim data transaksi dan total pendapatan ke view
        return view('laporan.penjualan', compact('transaksis', 'totalPendapatan'));
    }
}