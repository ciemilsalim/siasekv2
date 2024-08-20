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
        Schema::table('barangs', function (Blueprint $table) {
            $table->foreignId('jenis_barang_id')->after('id')->constrained('jenis_barangs')->cascadeOnDelete();
            $table->foreignId('ruang_id')->after('tahun_pembelian')->constrained('ruangs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn('jenis_barang_id');
            $table->dropColumn('ruang_id');
        });
    }
};
