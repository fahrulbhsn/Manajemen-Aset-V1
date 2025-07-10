<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Izinkan semua kolom diisi kecuali ID

    // Relasi: Satu transaksi milik satu Aset
    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }

    // Relasi: Satu transaksi dicatat oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}