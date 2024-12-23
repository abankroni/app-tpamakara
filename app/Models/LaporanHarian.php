<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    use HasFactory;

    protected $table = 'laporan_harian';

    protected $fillable = [
        'siswa_id',
        'tanggal_pelaksanaan',
        'tema_harian_id',
        'subtema_harian_id',
        'kegiatan_fisik_motorik',
        'kemampuan_fisik_motorik',
        'kegiatan_kognitif',
        'kemampuan_kognitif',
        'sosial_emosi',
        'catatan_khusus',
        'snack',
        'makan_siang',
        'tidur_siang',
        'guru_id',
        'koordinator_id',
        'status_approval',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function temaHarian()
    {
        return $this->belongsTo(TemaHarian::class, 'tema_harian_id');
    }

    public function subtemaHarian()
    {
        return $this->belongsTo(SubtemaHarian::class, 'subtema_harian_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function koordinator() {
        return $this->belongsTo(Guru::class, 'koordinator_id');
    }
}
