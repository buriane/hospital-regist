<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    use HasFactory;
    protected $table = 'polikliniks';
    protected $primaryKey = 'id_poliklinik';
    protected $guarded = ['id_poliklinik'];
    protected $fillable = [
        'nama_poliklinik',
    ];

    public function dokter()
    {
        return $this->hasMany(Dokter::class, 'id_poliklinik');
    }

    public function jadwalDokter()
    {
        return $this->hasManyThrough(JadwalDokter::class, Dokter::class, 'id_poliklinik', 'id_dokter', 'id_poliklinik', 'id_dokter');
    }

    public function jadwalKhususDokter()
    {
        return $this->hasManyThrough(JadwalKhususDokter::class, Dokter::class, 'id_poliklinik', 'id_dokter', 'id_poliklinik', 'id_dokter');
    }

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'id_poliklinik');
    }
}
