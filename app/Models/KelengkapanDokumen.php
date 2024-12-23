<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelengkapanDokumen extends Model
{
    use HasFactory;

    protected $table = 'kelengkapan_dokumen';

    protected $fillable = [
        'pendaftaran_trial_class_id',
        'akta_lahir_anak',
        'kartu_keluarga',
        'ktp_orang_tua',
        'npwp_orang_tua',
        'foto_anak',
        // Tambahkan kolom lain sesuai kebutuhan
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'pendaftaran_trial_class_id', 'pendaftaran_trial_class_id');
    }

    public function pendaftaranTrialClass(): BelongsTo
    {
        return $this->belongsTo(PendaftaranTrialClass::class, 'pendaftaran_trial_class_id', 'pendaftaran_trial_class_id');
    }
}
