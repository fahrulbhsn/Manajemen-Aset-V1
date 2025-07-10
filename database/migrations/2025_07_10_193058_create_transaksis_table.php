<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aset_id')->constrained('asets'); // Relasi ke tabel asets
            $table->foreignId('user_id')->constrained('users'); // Relasi ke user yang mencatat
            $table->string('nama_pembeli');
            $table->string('kontak_pembeli');
            $table->integer('harga_jual_akhir');
            $table->date('tanggal_jual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
