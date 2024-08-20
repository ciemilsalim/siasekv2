<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }

    public function murids(): HasMany
    {
        return $this->hasMany(Murid::class);
    }
}
