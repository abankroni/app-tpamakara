<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanTrialClass extends Model
{
    use HasFactory;

    protected $table = 'laporan_trial_class';

    protected $fillable = [
        'pendaftaran_id',
        'tanggal_pelaksanaan',
        'aspek_motorik',
        'aspek_kognitif',
        'aspek_sosial_emosi',
        'aspek_kemandirian',
        'guru_id',
        'koordinator_id',
        'status_approval',
        'kesimpulan'
        // Tambahkan kolom lain sesuai kebutuhan
    ];

    public function pendaftaranTrialClass()
    {
        return $this->belongsTo(PendaftaranTrialClass::class, 'pendaftaran_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function koordinator() {
        return $this->belongsTo(Guru::class, 'koordinator_id');
    }

}
