<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanLabaRugiExport implements FromCollection, WithHeadings, WithMapping
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
            'Aset Terjual',
            'Harga Jual',
            'Harga Beli (Modal)',
            'Laba'
        ];
    }

    public function map($transaksi): array
    {
        $harga_beli = $transaksi->aset->harga_beli ?? 0;
        $laba = $transaksi->harga_jual_akhir - $harga_beli;

        return [
            $transaksi->aset->nama_aset ?? 'Aset Dihapus',
            $transaksi->harga_jual_akhir,
            $harga_beli,
            $laba,
        ];
    }
}