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
        Schema::table('peminjams', function (Blueprint $table) {
            $table->foreignId('guru_id')->after('id')->constrained('gurus')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjams', function (Blueprint $table) {
            $table->dropColumn('guru_id');
        });
    }
};
