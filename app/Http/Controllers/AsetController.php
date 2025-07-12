<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Status;
use Illuminate\Support\Facades\File; // Import Facade File untuk menghapus file

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data aset, termasuk data relasinya (kategori & status)
        $asets = Aset::with(['kategori', 'status'])->latest()->get(); 
        return view('aset.index', compact('asets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua data kategori dan status untuk ditampilkan di dropdown
        $kategoris = Kategori::all();
        $statuses = Status::whereIn('name', ['Tersedia', 'Perbaikan'])->get(); // Hanya tampilkan status yang relevan
        return view('aset.create', compact('kategoris', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'status_id' => 'required|exists:statuses,id',
            'tanggal_beli' => 'required|date',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'detail' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
        ]);

        $data = $request->all();

        // LOGIKA UNGGAH FOTO DIMULAI DI SINI (untuk membuat aset baru)
        if ($request->hasFile('foto')) {
            $namaFile = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('foto_aset'), $namaFile);
            $data['foto'] = $namaFile;
        }
        // LOGIKA UNGGAH FOTO SELESAI

        Aset::create($data);

        return redirect()->route('aset.index')->with('success', 'Aset baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Aset $aset)
    {
        // Method ini tidak digunakan dalam konteks ini, bisa ditambahkan logika view detail jika diperlukan
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aset $aset)
    {
        // Ambil data untuk dropdown
        $kategoris = Kategori::all();
        // Perbaiki baris ini untuk tidak menyertakan "Terjual"
        $statuses = Status::where('name', '!=', 'Terjual')->get(); 
        return view('aset.edit', compact('aset', 'kategoris', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aset $aset)
    {
        // Validasi data
        $request->validate([
            'nama_aset' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'status_id' => 'required|exists:statuses,id',
            'tanggal_beli' => 'required|date',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'detail' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar untuk update
        ]);

        $data = $request->all();

        // LOGIKA CEK PERUBAHAN STATUS DAN UPDATE tanggal_update
        // Jika status_id yang baru berbeda dengan status_id yang lama
        if ($request->status_id != $aset->status_id) {
            $data['tanggal_update'] = now(); // Set tanggal_update ke waktu saat ini
        } else {
            // Jika status tidak berubah, pastikan tanggal_update yang sudah ada tetap dipertahankan
            // Laravel secara otomatis akan mempertahankan nilai jika tidak ada di $data,
            // tapi ini untuk kejelasan dan mencegah potensi overwrite jika ada logika lain.
            $data['tanggal_update'] = $aset->tanggal_update;
        }


        // LOGIKA UNTUK MENGGANTI/MEMPERBARUI FOTO DIMULAI DI SINI
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada dan file-nya benar-benar ada di server
            if ($aset->foto && File::exists(public_path('foto_aset/' . $aset->foto))) {
                File::delete(public_path('foto_aset/' . $aset->foto));
            }

            // Unggah foto baru
            $namaFile = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('foto_aset'), $namaFile);
            $data['foto'] = $namaFile;
        } else {
            // Jika tidak ada foto baru diunggah, pastikan foto lama tetap dipertahankan
            // Ini penting karena $request->all() tidak akan menyertakan 'foto' jika tidak ada file baru
            // dan kita tidak ingin menghapus foto yang sudah ada secara tidak sengaja.
            $data['foto'] = $aset->foto; 
        }
        // LOGIKA UNTUK MENGGANTI/MEMPERBARUI FOTO SELESAI

        $aset->update($data);

        return redirect()->route('aset.index')->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
/**
     * Remove the specified resource from storage.
     */
    public function destroy(Aset $aset)
    {
        // Langkah 1: Hapus semua transaksi yang terkait dengan aset ini.
        // Fungsi transaksis() adalah yang baru saja kita buat di Model Aset.
        $aset->transaksis()->delete();

        // Langkah 2: Hapus file foto dari server jika ada.
        if ($aset->foto && file_exists(public_path('foto_aset/' . $aset->foto))) {
            unlink(public_path('foto_aset/' . $aset->foto));
        }

        // Langkah 3: Setelah semua yang terkait dengannya dihapus, baru hapus asetnya.
        $aset->delete();

        // Langkah 4: Kembalikan ke halaman daftar dengan pesan sukses.
        return redirect()->route('aset.index')
                         ->with('success', 'Aset dan semua riwayat transaksinya berhasil dihapus.');
    }
}
