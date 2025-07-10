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
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable(); // Foto tidak wajib diisi
            $table->foreignId('kategori_id')->constrained('kategoris'); // Relasi ke tabel kategoris
            $table->foreignId('status_id')->constrained('statuses'); // Relasi ke tabel statuses
            $table->string('nama_aset');
            $table->date('tanggal_beli');
            $table->date('tanggal_update')->nullable(); // Tidak wajib diisi
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->text('detail')->nullable(); // Detail tidak wajib diisi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};
