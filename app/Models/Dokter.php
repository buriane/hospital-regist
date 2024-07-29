<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'dokters';
    protected $primaryKey = 'id_dokter';
    protected $guarded = ['id_dokter'];
    protected $fillable = [
        'id_poliklinik',
        'nama_dokter',
    ];

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'id_poliklinik');
    }

    public function jadwalDokters()
    {
        return $this->hasMany(JadwalDokter::class, 'id_dokter');
    }

    public function jadwalDokter()
    {
        return $this->hasOne(JadwalDokter::class, 'id_dokter');
    }

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'id_dokter');
    }

    public function cutiDokter()
    {
        return $this->hasMany(CutiDokter::class, 'id_dokter');
    }

    public function cutiDokters()
    {
        return $this->hasMany(CutiDokter::class, 'id_dokter');
    }

    public function isCuti()
    {
        return $this->cutiDokter()
                    ->where('tanggal_mulai', '<=', now())
                    ->where('tanggal_selesai', '>=', now())
                    ->exists();
    }

    public function jadwalKhususDokters()
    {
        return $this->hasMany(JadwalKhususDokter::class, 'id_dokter');
    }
}
