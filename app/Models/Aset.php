<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    // Izinkan semua kolom untuk diisi kecuali ID
    protected $guarded = ['id'];

    // Definisikan relasi ke Model Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Definisikan relasi ke Model Status
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function transaksis()
    {
    return $this->hasMany(Transaksi::class);
    }
}