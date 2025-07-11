<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transaksis;

    public function __construct($transaksis)
    {
        $this->transaksis = $transaksis;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->transaksis;
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Aset Terjual',
            'Tanggal Jual',
            'Harga Jual Akhir',
            'Nama Pembeli',
            'Kontak Pembeli',
            'Dicatat oleh',
        ];
    }

    public function map($transaksi): array
    {
        return [
            'TRX-' . $transaksi->id,
            $transaksi->aset->nama_aset ?? 'Aset Dihapus',
            $transaksi->tanggal_jual,
            $transaksi->harga_jual_akhir,
            $transaksi->nama_pembeli,
            $transaksi->kontak_pembeli,
            $transaksi->user->name,
        ];
    }
}