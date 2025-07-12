<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Aset;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data transaksi, termasuk data relasi ke Aset dan User
        $transaksis = Transaksi::with(['aset', 'user'])->latest()->get();
        
        return view('transaksi.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua aset yang statusnya "Tersedia" untuk ditampilkan di dropdown
        $asets = Aset::whereHas('status', function($query) {
            $query->where('name', 'Tersedia');
        })->get();
        
        return view('transaksi.create', compact('asets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // 1. Validasi data yang masuk, termasuk metode_pembayaran
    $request->validate([
        'aset_id' => 'required|exists:asets,id',
        'nama_pembeli' => 'required|string|max:255',
        'kontak_pembeli' => 'required|string|max:255',
        'harga_jual_akhir' => 'required|integer',
        'tanggal_jual' => 'required|date',
        'metode_pembayaran' => 'required|string|in:Tunai,Transfer Bank,QRIS', // Validasi baru
    ]);

    // 2. Simpan data transaksi baru, termasuk metode_pembayaran
    Transaksi::create([
        'aset_id' => $request->aset_id,
        'user_id' => Auth::id(),
        'nama_pembeli' => $request->nama_pembeli,
        'kontak_pembeli' => $request->kontak_pembeli,
        'harga_jual_akhir' => $request->harga_jual_akhir,
        'tanggal_jual' => $request->tanggal_jual,
        'metode_pembayaran' => $request->metode_pembayaran, // <-- Data baru ditambahkan di sini
    ]);

    // 3. Update status aset yang terjual
    $aset = Aset::find($request->aset_id);
    $statusTerjual = Status::where('name', 'Terjual')->first();

    if ($aset && $statusTerjual) {
        $aset->status_id = $statusTerjual->id;
        $aset->tanggal_update = now();
        $aset->save();
    }

    // 4. Kembali ke halaman daftar dengan pesan sukses
    return redirect()->route('transaksi.index')
                     ->with('success', 'Transaksi berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}