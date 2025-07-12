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
        Schema::table('transaksis', function (Blueprint $table) {
            // Tambahkan kolom baru setelah kolom 'harga_jual_akhir'
            $table->string('metode_pembayaran')->after('harga_jual_akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('metode_pembayaran');
        });
    }
};
