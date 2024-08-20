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
            $table->dropColumn('status_barang');
            $table->dropColumn('status_kepemilikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->enum('status_barang', ['aktif', 'tidak aktif']);
            $table->enum('status_kepemilikan', ['milik', 'bukan_milik']);
        });
    }
};
