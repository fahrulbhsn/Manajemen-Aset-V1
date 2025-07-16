<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPembelianExport implements FromCollection, WithHeadings, WithMapping
{
    protected $asets;

    public function __construct($asets)
    {
        $this->asets = $asets;
    }

    public function collection()
    {
        return $this->asets;
    }

    public function headings(): array
    {
        return [
            'Nama Aset',
            'Kategori',
            'Tanggal Beli',
            'Harga Beli'
        ];
    }

    public function map($aset): array
    {
        return [
            $aset->nama_aset,
            $aset->kategori->name,
            $aset->tanggal_beli,
            $aset->harga_beli,
        ];
    }
}