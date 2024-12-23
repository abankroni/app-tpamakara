<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Siswa extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto_anak')->singleFile();
        $this->addMediaCollection('akta_lahir_anak')->singleFile();
    }

    public function hasMedia(string $collectionName = ''): bool
    {
        return $this->getMedia($collectionName)->isNotEmpty();
    }


    protected $table = 'siswa';

    // Definisikan kolom yang bisa diisi secara massal
    protected $fillable = [
        'pendaftaran_trial_class_id',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'urutan_anak_dalam_keluarga',
        'program_kelas_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'kebiasaan_makan',
        'kebiasaan_minum',
        'kebiasaan_tidur',
        'kebiasaan_bakbab',
        'catatan_khusus_medis',
        'deskripsi_catatan_medis',
        'penyakit_berat',
        'keadaan_anak',
        'sifat_baik',
        'sifat_perlu_perhatian',
        'foto_anak',
        'akta_lahir_anak',
        // Tambahkan kolom lain sesuai kebutuhan
    ];

    public function pendaftaranTrialClass()
    {
        return $this->belongsTo(PendaftaranTrialClass::class, 'pendaftaran_trial_class_id');
    }

    public function detailOrangTua(): HasOne
    {
        return $this->hasOne(DetailOrangTua::class, 'pendaftaran_trial_class_id', 'pendaftaran_trial_class_id');
    }

    public function kelengkapanDokumen(): HasOne
    {
        return $this->hasOne(KelengkapanDokumen::class, 'pendaftaran_trial_class_id', 'pendaftaran_trial_class_id');
    }

    public function laporanTrialClass()
    {
        return $this->hasMany(LaporanTrialClass::class);
    }

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class);
    }

    public function programKelas()
    {
        return $this->belongsTo(Program::class, 'program_kelas_id');
    }
}
