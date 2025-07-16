<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPenjualanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transaksis;

    public function __construct($transaksis)
    {
        $this->transaksis = $transaksis;
    }

    public function collection()
    {
        return $this->transaksis;
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Aset Terjual',
            'Tgl Jual',
            'Harga Akhir',
            'Pembeli',
            'Dicatat oleh'
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
            $transaksi->user->name,
        ];
    }
}