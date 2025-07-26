<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Aset;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $search = $request->input('search');

        $query = Transaksi::with(['aset', 'user'])->latest();

        // Terapkan filter tanggal
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
        }

        // Terapkan filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                // Cari berdasarkan ID Transaksi cukup angka
                if (is_numeric($search)) {
                    $q->where('id', $search);
                }

                // Cari berdasarkan Nama Aset, Nama Pembeli, atau Nama User yang Mencatat
                $q->orWhere('nama_pembeli', 'like', '%' . $search . '%')
                    ->orWhereHas('aset', function ($q_aset) use ($search) {
                        $q_aset->where('nama_aset', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user', function ($q_user) use ($search) {
                        $q_user->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $transaksis = $query->paginate(10);

        return view('transaksi.index', compact('transaksis', 'tanggal_awal', 'tanggal_akhir', 'search'));
    }

    public function create()
    {
        $asets = Aset::whereHas('status', function ($query) {
            $query->where('name', 'Tersedia');
        })->get();

        return view('transaksi.create', compact('asets'));
    }

    public function store(Request $request)
    {
        // Validasi data masukan
        $validatedData = $request->validate([
            'aset_id' => 'required|exists:asets,id',
            'nama_pembeli' => 'required|string|max:255',
            'kontak_pembeli' => 'required|string|max:255',
            'harga_jual_akhir' => 'required|integer',
            'tanggal_jual' => 'required|date',
            'metode_pembayaran' => 'required|string|in:Tunai,Transfer Bank,QRIS',
        ]);

        // Tambahkan user_id dari pengguna yang sedang login
        $validatedData['user_id'] = Auth::id();

        // Cari aset berdasarkan aset_id
        $aset = Aset::find($validatedData['aset_id']);

        // Periksa apakah aset ada dan statusnya "Tersedia"
        if (!$aset || $aset->status->name !== 'Tersedia') {
            return back()->with('error', 'Aset tidak ditemukan atau sudah tidak tersedia.');
        }

        // Simpan data transaksi baru
        $transaksi = Transaksi::create($validatedData);

        // Update status aset menjadi "Terjual" dan tanggal update
        $statusTerjual = Status::where('name', 'Terjual')->first();
        if ($statusTerjual) {
            $aset->status_id = $statusTerjual->id;
            $aset->tanggal_update = now();
            $aset->save();
        }

        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'menambah',
            'description' => "Menambah transaksi 'TRX-{$transaksi->id}' untuk aset '{$aset->nama_aset}'"
        ]);

        // Arahkan ke halaman detail transaksi dengan pesan sukses
        return redirect()->route('transaksi.show', $transaksi->id)
            ->with('success', 'Transaksi berhasil dicatat! Anda bisa mencetak struk di sini.');
    }

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

        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'mengubah',
            'description' => "Mengubah transaksi 'TRX-{$transaksi->id}'"
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi dan mengembalikan status aset.
     */
    public function destroy(Transaksi $transaksi)
    {
        //Ambil semua informasi yang dibutuhkan untuk log SEBELUM data dihapus.
        $namaAset = $transaksi->aset->nama_aset ?? 'Aset Dihapus';
        $transaksiId = $transaksi->id;

        //Kembalikan status aset menjadi "Tersedia"
        $aset = Aset::find($transaksi->aset_id);
        $statusTersedia = Status::where('name', 'Tersedia')->first();

        if ($aset && $statusTersedia) {
            $aset->status_id = $statusTersedia->id;
            $aset->tanggal_update = now();
            $aset->save();
        }

        //Hapus data transaksi
        $transaksi->delete();

        //Catat log aktivitas
        \App\Models\ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'menghapus',
            'description' => "Menghapus transaksi 'TRX-{$transaksiId}' untuk aset '{$namaAset}'"
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus dan status aset telah dikembalikan.');
    }

    /**
     * Menampilkan halaman struk untuk dicetak.
     */
    public function cetak_struk(Transaksi $transaksi)
    {
        // Mengirim data transaksi yang spesifik ke halaman view 'transaksi.struk'
        return view('transaksi.struk', compact('transaksi'));
    }

    /**
     * Menampilkan detail transaksi tertentu.
     */
    public function show(Transaksi $transaksi)
    {
        // Mengirim data transaksi yang spesifik, beserta relasinya,
        // ke halaman view 'transaksi.show'
        $transaksi->load(['aset', 'user']);
        return view('transaksi.show', compact('transaksi'));
    }
}