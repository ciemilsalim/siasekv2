<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    // Fungsi untuk mendapatkan kode barang berikutnya
    public static function getNextKodeBarang()
    {
        $lastBarang = self::orderBy('id', 'desc')->first();
        if ($lastBarang) {
            $lastKode = substr($lastBarang->kode_barang, 2); // Ambil bagian angka setelah 'B-'
            $nextKode = intval($lastKode) + 1;
            return 'B-' . str_pad($nextKode, 5, '0', STR_PAD_LEFT);
        } else {
            return 'B-00001';
        }
    }
   
    public function jenis_barang(): BelongsTo
    {
        return $this->belongsTo(JenisBarang::class);
    }

    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class);
    }

   public function peminjam(): HasOne
   {
       return $this->hasOne(Peminjam::class);
   }
}
