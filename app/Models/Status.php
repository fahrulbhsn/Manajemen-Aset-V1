<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- BARIS INI YANG MEMPERBAIKI EROR
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    // Izinkan kolom 'name' untuk diisi
    protected $fillable = [
        'name',
    ];
}