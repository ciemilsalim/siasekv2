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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('jenis_barang');
            $table->string('merk_barang');
            $table->enum('kondisi_barang', ['baik', 'rusak ringan', 'rusak berat']);
            $table->string('tahun_pembelian');
            $table->string('lokasi_penyimpanan');
            $table->enum('status_barang', ['aktif', 'tidak aktif']);
            $table->enum('status_kepemilikan', ['milik', 'bukan milik']);
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
