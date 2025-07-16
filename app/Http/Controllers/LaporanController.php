<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;
use App\Exports\LaporanPembelianExport;
use App\Exports\LaporanLabaRugiExport;
use App\Models\Aset;
use PDF;

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
     * Cetak laporan penjualan ke Excel.
     */
    public function export_penjualan_excel(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $query = Transaksi::query();
        
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
        }
        
        $transaksis = $query->latest()->get();
        
        return Excel::download(new LaporanPenjualanExport($transaksis), 'laporan-penjualan.xlsx');
    }

    public function export_pembelian_excel(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $query = Aset::query();
        
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_beli', [$tanggal_awal, $tanggal_akhir]);
        }
        
        $asets = $query->latest()->get();
        
        return Excel::download(new LaporanPembelianExport($asets), 'laporan-pembelian.xlsx');
    }

    public function export_laba_rugi_excel(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $query = Transaksi::query();
        
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
        }
        
        $transaksis = $query->with('aset')->latest()->get();
        
        return Excel::download(new LaporanLabaRugiExport($transaksis), 'laporan-laba-rugi.xlsx');
    }
    /**
     * CETAK LAPORAN KE PDF
     */
    public function cetak_penjualan_pdf(Request $request)
    {
        
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $query = Transaksi::query();
        if ($tanggal_awal && $tanggal_akhir) {
        $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
    }
        $transaksis = $query->with('aset')->latest()->get();
        $totalPendapatan = $transaksis->sum('harga_jual_akhir');

        // Membuat PDF dari view 'laporan.penjualan_pdf'
        $pdf = PDF::loadView('laporan.penjualan_pdf', compact('transaksis', 'totalPendapatan', 'tanggal_awal', 'tanggal_akhir'));
        return $pdf->stream('laporan-penjualan-'.date('Y-m-d').'.pdf');
    }

    public function cetak_pembelian(Request $request)
    {
        // Logika ini sama dengan fungsi laporan pembelian
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $query = Aset::latest();
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_beli', [$tanggal_awal, $tanggal_akhir]);
        }
        $asets = $query->get();

        // Buat PDF
        $pdf = PDF::loadView('laporan.pembelian_pdf', compact('asets', 'tanggal_awal', 'tanggal_akhir'));
        return $pdf->stream('laporan-pembelian.pdf');
    }

    public function pembelian(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $query = Aset::latest();
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_beli', [$tanggal_awal, $tanggal_akhir]);
        }
        $asets = $query->get();
        $totalPengeluaran = $asets->sum('harga_beli');

        return view('laporan.pembelian', compact('asets', 'totalPengeluaran', 'tanggal_awal', 'tanggal_akhir'));
    }
    public function laba_rugi(Request $request)
    {
        // Ambil tanggal awal dan akhir dari filter, jika ada
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        // Mulai query ke tabel transaksi
        $query = Transaksi::query();
        // Terapkan filter tanggal jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
        }
        // Ambil semua transaksi yang relevan
        $transaksis = $query->with('aset')->latest()->get();
        // Hitung metrik keuangan
        $totalPendapatan = $transaksis->sum('harga_jual_akhir');
        $totalModal = $transaksis->sum(function($transaksi) {
            // Pastikan aset masih ada untuk diambil harga belinya
            return $transaksi->aset->harga_beli ?? 0;
        });
        $labaBersih = $totalPendapatan - $totalModal;

        // Kirim semua data ke view
        return view('laporan.laba_rugi', compact('transaksis', 'totalPendapatan', 'totalModal', 'labaBersih'));
    }

    public function cetak_laba_rugi(Request $request)
    {
        // Logika ini sama dengan fungsi laporan laba rugi
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $query = Transaksi::query();
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
        }
        $transaksis = $query->with('aset')->latest()->get();
        $totalPendapatan = $transaksis->sum('harga_jual_akhir');
        $totalModal = $transaksis->sum(fn($t) => $t->aset->harga_beli ?? 0);
        $labaBersih = $totalPendapatan - $totalModal;

        // Buat PDF
        $pdf = PDF::loadView('laporan.laba_rugi_pdf', compact('transaksis', 'totalPendapatan', 'totalModal', 'labaBersih', 'tanggal_awal', 'tanggal_akhir'));
        return $pdf->stream('laporan-laba-rugi.pdf');
    }
}
