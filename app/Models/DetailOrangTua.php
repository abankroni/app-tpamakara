<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DetailOrangTua extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('kartu_keluarga')->singleFile();
        $this->addMediaCollection('ktp_orang_tua')->singleFile();
        $this->addMediaCollection('npwp_orang_tua')->singleFile();
    }

    public function hasMedia(string $collectionName = ''): bool
    {
        return $this->getMedia($collectionName)->isNotEmpty();
    }

    protected $table = 'detail_orang_tua';

    protected $fillable = [
        'siswa_id',
        'nama_lengkap_ayah',
        'tanggal_lahir_ayah',
        'agama_ayah',
        'pend_terakhir_ayah',
        'alamat_ayah',
        'email_ayah',
        'no_hp_ayah',
        'pekerjaan_ayah',
        'institusi_ayah',
        'alamat_institusi_ayah',
        'nama_lengkap_ibu',
        'tanggal_lahir_ibu',
        'agama_ibu',
        'pend_terakhir_ibu',
        'alamat_ibu',
        'email_ibu',
        'no_hp_ibu',
        'pekerjaan_ibu',
        'institusi_ibu',
        'alamat_institusi_ibu',
        'nama_lengkap_wali',
        'tanggal_lahir_wali',
        'pend_terakhir_wali',
        'alamat_wali',
        'no_hp_wali',
        'status_hubungan_wali',
        'kartu_keluarga',
        'ktp_orang_tua',
        'npwp_orang_tua',
        // Tambahkan kolom lain sesuai kebutuhan
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function pendaftaranTrialClass(): BelongsTo
    {
        return $this->belongsTo(PendaftaranTrialClass::class, 'pendaftaran_trial_class_id', 'id');
    }
}
