<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbsensiGuru extends Model
{
    use HasFactory;

    protected $guarded = [];

   
   /**
    * Get the guru that owns the AbsensiGuru
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function guru(): BelongsTo
   {
       return $this->belongsTo(Guru::class);
   }
}
