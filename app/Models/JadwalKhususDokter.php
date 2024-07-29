<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKhususDokter extends Model
{
    use HasFactory;
    protected $table = 'jadwal_khusus_dokters';
    protected $primaryKey = 'id_jadwal_khusus_dokter';
    protected $guarded = ['id_jadwal__khusus_dokter'];
    protected $fillable = [
        'id_dokter',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'kuota',
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'id_jadwal_dokter');
    }

    public function poliklinik()
    {
        return $this->hasOneThrough(Poliklinik::class, Dokter::class, 'id_dokter', 'id_poliklinik', 'id_dokter', 'id_poliklinik');
    }
}
