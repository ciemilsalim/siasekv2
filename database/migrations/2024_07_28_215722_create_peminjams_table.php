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
        Schema::create('peminjams', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peminjam');
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_pengembalian');
            $table->enum('kondisi_barang_kembali', ['baik', 'rusak ringan', 'rusak berat']);
            $table->string('keterangan_peminjaman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjams');
    }
};
