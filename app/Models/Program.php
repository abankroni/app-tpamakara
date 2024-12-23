<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'program_kelas';

    protected $fillable = [
        'nama_kelas',
        'deskripsi',
        'durasi',
        'harga',
    ];

    public function pendaftaran()
    {
        return $this->hasMany(PendaftaranTrialClass::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
