<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransaksiExport;
use App\Models\Aset;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan penjualan.
     */
    public function penjualan(Request $request)
    {
    // Ambil tanggal awal dan akhir dari request, jika ada
    $tanggal_awal = $request->input('tanggal_awal');
    $tanggal_akhir = $request->input('tanggal_akhir');

    // Mulai query ke tabel transaksi
    $query = Transaksi::query();

    // Jika ada input tanggal, filter datanya
    if ($tanggal_awal && $tanggal_akhir) {
        $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
    }

    // Ambil data yang sudah difilter
    $transaksis = $query->latest()->get();

    // Hitung total pendapatan HANYA dari data yang sudah difilter
    $totalPendapatan = $transaksis->sum('harga_jual_akhir');

    // Kirim data ke view
    return view('laporan.penjualan', compact('transaksis', 'totalPendapatan'));
    }

    /**
     * Cetak laporan penjualan ke PDF.
     */
    public function cetak_pdf(Request $request)
    {
        // Logikanya sama persis dengan fungsi penjualan untuk mengambil data
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $query = Transaksi::query();
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
        }
        $transaksis = $query->latest()->get();
        $totalPendapatan = $transaksis->sum('harga_jual_akhir');

        // Membuat PDF
        $pdf = \PDF::loadView('laporan.pdf', compact('transaksis', 'totalPendapatan', 'tanggal_awal', 'tanggal_akhir'));

        // Mengunduh PDF dengan nama file dinamis
        return $pdf->download('laporan-penjualan.pdf');
    }
    /**
     * Cetak laporan penjualan ke Excel.
     */
    public function cetak_excel(Request $request)
    {
        // Logika untuk mengambil data (sama persis seperti sebelumnya)
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $query = Transaksi::query();
        if ($tanggal_awal && $tanggal_akhir) {
        $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
        }
        $transaksis = $query->latest()->get();

        // Mengunduh file Excel
        return Excel::download(new TransaksiExport($transaksis), 'laporan-penjualan.xlsx');
    }

        public function pembelian(Request $request)
    {
        // Ambil tanggal awal dan akhir dari filter, jika ada
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Mulai query ke tabel aset
        $query = Aset::query();

        // Jika ada input tanggal, filter berdasarkan tanggal_beli
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_beli', [$tanggal_awal, $tanggal_akhir]);
        }

        // Ambil data aset yang sudah difilter
        $asets = $query->latest()->get();

        // Hitung total pengeluaran dari harga beli aset yang difilter
        $totalPengeluaran = $asets->sum('harga_beli');

        // Kirim data ke view
        return view('laporan.pembelian', compact('asets', 'totalPengeluaran'));
    }
}