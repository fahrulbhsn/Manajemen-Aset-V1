<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Aset;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // ... (fungsi index, create, dan store Anda sudah ada di sini dan sudah benar) ...
    public function index()
    {
        $transaksis = Transaksi::with(['aset', 'user'])->latest()->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $asets = Aset::whereHas('status', function($query) {
            $query->where('name', 'Tersedia');
        })->get();
        return view('transaksi.create', compact('asets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aset_id' => 'required|exists:asets,id',
            'nama_pembeli' => 'required|string|max:255',
            'kontak_pembeli' => 'required|string|max:255',
            'harga_jual_akhir' => 'required|integer',
            'tanggal_jual' => 'required|date',
            'metode_pembayaran' => 'required|string|in:Tunai,Transfer Bank,QRIS',
        ]);

        Transaksi::create($request->all() + ['user_id' => Auth::id()]);

        $aset = Aset::find($request->aset_id);
        $statusTerjual = Status::where('name', 'Terjual')->first();
        if ($aset && $statusTerjual) {
            $aset->status_id = $statusTerjual->id;
            $aset->tanggal_update = now();
            $aset->save();
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dicatat.');
    }


    // ==================================
    // FUNGSI BARU DIMULAI DI SINI
    // ==================================

    /**
     * Menampilkan formulir untuk mengedit transaksi.
     */
    public function edit(Transaksi $transaksi)
    {
        // Kita hanya perlu mengirim data transaksi yang akan diedit
        return view('transaksi.edit', compact('transaksi'));
    }

    /**
     * Memperbarui data transaksi di database.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'kontak_pembeli' => 'required|string|max:255',
            'harga_jual_akhir' => 'required|integer',
            'tanggal_jual' => 'required|date',
            'metode_pembayaran' => 'required|string|in:Tunai,Transfer Bank,QRIS',
        ]);

        $transaksi->update($request->all());

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi dan mengembalikan status aset.
     */
    public function destroy(Transaksi $transaksi)
    {
        // (Logika Otomatisasi) Kembalikan status aset menjadi "Tersedia"
        $aset = Aset::find($transaksi->aset_id);
        $statusTersedia = Status::where('name', 'Tersedia')->first();

        if ($aset && $statusTersedia) {
            $aset->status_id = $statusTersedia->id;
            $aset->tanggal_update = now();
            $aset->save();
        }

        // Hapus data transaksi
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus dan status aset telah dikembalikan.');
    }
}