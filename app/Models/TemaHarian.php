<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemaHarian extends Model
{
    use HasFactory;

    protected $table = 'tema_harian';

    protected $fillable = [
        'tema',
    ];

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'tema_harian_id');
    }

    public function subtemas()
    {
        return $this->hasMany(SubtemaHarian::class, 'tema_id');
    }
}
