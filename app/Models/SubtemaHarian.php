<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubtemaHarian extends Model
{
    use HasFactory;

    protected $table = 'subtema_harian';

    protected $fillable = [
        'tema_id',
        'subtema',
        'detail_fisik_motorik',
        'detail_kognitif',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['created_at', 'updated_at', 'your_date_field'];

    public function tema()
    {
        return $this->belongsTo(TemaHarian::class, 'tema_id');
    }

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'subtema_harian_id');
    }
}
