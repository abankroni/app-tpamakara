<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nama_guru',
        'jenis_kelamin',
        'tanggal_lahir',
        'agama',
        'pend_terakhir',
        'alamat',
        'email',
        'no_handphone',
        'peran',
        'status_guru'
        // Tambahkan kolom lain sesuai kebutuhan
    ];

    public function laporanTrialClass()
    {
        return $this->hasMany(LaporanTrialClass::class, 'guru_id');
    }

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class, 'guru_id');
    }

    // Di dalam model Guru
    public function scopeGurus($query)
    {
        return $query->where('peran', 'Guru'); // Mengambil hanya yang memiliki peran 'Guru'
    }

    public function scopeKoordinators($query)
    {
        return $query->where('peran', 'Koordinator'); // Mengambil hanya yang memiliki peran 'Koordinator'
    }

}
