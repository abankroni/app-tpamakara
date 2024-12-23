<?php

namespace App\Models;

use App\Livewire\DetailOrangTua;
use App\Livewire\KelengkapanDokumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranTrialClass extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_trial_class';

    protected $fillable = [
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_lengkap_ayah',
        'nama_lengkap_ibu',
        'alamat_domisili',
        'no_handphone',
        'nama_pengantar',
        'program_kelas_id',
        'tanggal_daftar',
        'status'
    ];


    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'pendaftaran_trial_class_id');
    }

    public function detailOrangTua()
    {
        return $this->hasMany(DetailOrangTua::class);
    }

    public function kelengkapanDokumen()
    {
        return $this->hasMany(KelengkapanDokumen::class);
    }

    public function programKelas()
    {
        return $this->belongsTo(Program::class, 'program_kelas_id');
    }

    public function laporanTrialClass()
    {
        return $this->hasMany(LaporanTrialClass::class, 'pendaftaran_id');
    }
}
