<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Transaksi;
use App\Models\Kategori;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function index()
    {
        $asetsToApprove = Aset::with(['kategori', 'status'])->where('approval_status', '!=', 'disetujui')->get();
        $transaksisToApprove = Transaksi::with(['aset.kategori', 'aset.status', 'user'])->where('approval_status', '!=', 'disetujui')->get();
        
        $kategoris = Kategori::pluck('name', 'id');
        $statuses = Status::pluck('name', 'id');

        return view('approval.index', compact('asetsToApprove', 'transaksisToApprove', 'kategoris', 'statuses'));
    }

    // --- LOGIKA PERSETUJUAN ASET ---
    public function approveAset(Aset $aset)
    {
        if ($aset->approval_status == 'menunggu persetujuan edit') {
            $pendingData = json_decode($aset->pending_data, true);

            // Jika ada foto baru yang disetujui, hapus foto lama
            if (isset($pendingData['foto'])) {
                if ($aset->foto && File::exists(public_path('foto_aset/' . $aset->foto))) {
                    File::delete(public_path('foto_aset/' . $aset->foto));
                }
            }

            $aset->update($pendingData);
            $aset->pending_data = null;
            $aset->approval_status = 'disetujui';
            $aset->save();
        } elseif ($aset->approval_status == 'menunggu persetujuan hapus') {
            $aset->delete();
        }
        return redirect()->route('approval.index')->with('success', 'Permintaan aset telah disetujui.');
    }

    public function rejectAset(Aset $aset)
    {
        if ($aset->approval_status == 'menunggu persetujuan edit' && $aset->pending_data) {
             $pendingData = json_decode($aset->pending_data, true);
             if (isset($pendingData['foto']) && File::exists(public_path('foto_aset/' . $pendingData['foto']))) {
                File::delete(public_path('foto_aset/' . $pendingData['foto']));
            }
        }
        $aset->pending_data = null;
        $aset->approval_status = 'disetujui';
        $aset->save();
        return redirect()->route('approval.index')->with('success', 'Permintaan aset telah ditolak.');
    }

    // --- LOGIKA PERSETUJUAN TRANSAKSI ---
    public function approveTransaksi(Transaksi $transaksi)
    {
        if ($transaksi->approval_status == 'menunggu persetujuan edit') {
            $pendingData = json_decode($transaksi->pending_data, true);
            $transaksi->update($pendingData);
            $transaksi->pending_data = null;
            $transaksi->approval_status = 'disetujui';
            $transaksi->save();
        } elseif ($transaksi->approval_status == 'menunggu persetujuan hapus') {
            $aset = Aset::find($transaksi->aset_id);
            if ($aset) {
                $statusTersedia = \App\Models\Status::where('name', 'Tersedia')->first();
                $aset->status_id = $statusTersedia->id;
                $aset->save();
            }
            $transaksi->delete();
        }
        return redirect()->route('approval.index')->with('success', 'Permintaan transaksi telah disetujui.');
    }

    public function rejectTransaksi(Transaksi $transaksi)
    {
        $transaksi->pending_data = null;
        $transaksi->approval_status = 'disetujui';
        $transaksi->save();
        return redirect()->route('approval.index')->with('success', 'Permintaan transaksi telah ditolak.');
    }
}