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
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $query = Transaksi::with(['aset', 'user'])->latest();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $searchId = str_ireplace('TRX-', '', $search);
                if (is_numeric($searchId) && $searchId > 0) {
                    $q->where('id', $searchId);
                }

                $q->orWhere('nama_pembeli', 'like', '%' . $search . '%')
                    ->orWhereHas('aset', function ($q_aset) use ($search) {
                        $q_aset->where('nama_aset', 'like', '%' . $search . '%');
                    });

                $q->orWhereHas('user', function ($q_user) use ($search) {
                    $q_user->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tanggal_jual', [$tanggal_awal, $tanggal_akhir]);
        }

        $transaksis = $query->paginate($perPage);

        return view('transaksi.index', compact('transaksis', 'search', 'perPage', 'tanggal_awal', 'tanggal_akhir'));
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
        // Validasi data
        $validatedData = $request->validate([
            'aset_id' => 'required|exists:asets,id',
            'nama_pembeli' => 'required|string|max:255',
            'kontak_pembeli' => 'required|string|max:255',
            'harga_jual_akhir' => 'required|integer',
            'tanggal_jual' => 'required|date',
            'metode_pembayaran' => 'required|string|in:Tunai,Transfer Bank,QRIS',
        ]);

        $validatedData['user_id'] = Auth::id();
        $aset = Aset::find($validatedData['aset_id']);
        if (!$aset || $aset->status->name !== 'Tersedia') {
            return back()->with('error', 'Aset tidak ditemukan atau sudah tidak tersedia.');
        }

        $transaksi = Transaksi::create($validatedData);

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

        return redirect()->route('transaksi.show', $transaksi->id)
            ->with('success', 'Transaksi berhasil dicatat! Anda bisa mencetak struk di sini.');
    }

    /**
     * Menampilkan formulir untuk mengedit transaksi.
     */
    public function edit(Transaksi $transaksi)
    {
        return view('transaksi.edit', compact('transaksi'));
    }

    /**
     * Memperbarui data transaksi di database.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        // Validasi data
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'kontak_pembeli' => 'required|string|max:255',
            'harga_jual_akhir' => 'required|integer',
            'tanggal_jual' => 'required|date',
            'metode_pembayaran' => 'required|string|in:Tunai,Transfer Bank,QRIS',
        ]);

        // Cek peran pengguna
        if (Auth::user()->role == 'admin') {

            $transaksi->update($request->all());

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'mengubah',
                'description' => "Mengubah transaksi 'TRX-{$transaksi->id}'"
            ]);

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');

        } else {

            $transaksi->pending_data = json_encode($request->except('_token', '_method'));
            $transaksi->approval_status = 'menunggu persetujuan edit';
            $transaksi->save();

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'mengajukan',
                'description' => "Mengajukan perubahan untuk transaksi 'TRX-{$transaksi->id}'"
            ]);

            return redirect()->route('transaksi.index')->with('success', 'Permintaan perubahan transaksi telah diajukan untuk persetujuan Admin.');
        }
    }

    /**
     * Menghapus transaksi dan mengembalikan status aset.
     */
    public function destroy(Transaksi $transaksi)
    {
        // Cek peran pengguna
        if (Auth::user()->role == 'admin') {
            $namaAset = $transaksi->aset->nama_aset ?? 'Aset Dihapus';
            $transaksiId = $transaksi->id;
            $aset = Aset::find($transaksi->aset_id);
            $statusTersedia = Status::where('name', 'Tersedia')->first();
            if ($aset && $statusTersedia) {
                $aset->status_id = $statusTersedia->id;
                $aset->tanggal_update = now();
                $aset->save();
            }

            $transaksi->delete();

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'menghapus',
                'description' => "Menghapus transaksi 'TRX-{$transaksiId}' untuk aset '{$namaAset}'"
            ]);

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus dan status aset telah dikembalikan.');

        } else {

            $transaksi->approval_status = 'menunggu persetujuan hapus';
            $transaksi->save();

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'mengajukan',
                'description' => "Mengajukan penghapusan untuk transaksi 'TRX-{$transaksi->id}'"
            ]);

            return redirect()->route('transaksi.index')->with('success', 'Permintaan penghapusan transaksi telah diajukan untuk persetujuan Admin.');
        }
    }

    /**
     * Menampilkan halaman struk untuk dicetak.
     */
    public function cetak_struk(Transaksi $transaksi)
    {
        return view('transaksi.struk', compact('transaksi'));
    }

    /**
     * Menampilkan detail transaksi tertentu.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['aset', 'user']);
        return view('transaksi.show', compact('transaksi'));
    }
}