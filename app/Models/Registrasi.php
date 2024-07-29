<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    use HasFactory;
    protected $table = 'registrasis';
    protected $primaryKey = 'id_registrasi';
    protected $guarded = ['id_registrasi'];
    protected $fillable = [
        'id_pasien',
        'tanggal_kunjungan',
        'id_poliklinik',
        'id_dokter',
        'kode_booking',
        'status',
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }

    public function jadwalDokter()
    {
        return $this->belongsTo(JadwalDokter::class, 'id_jadwal_dokter');
    }

    public function jadwalKhususDokter()
    {
        return $this->belongsTo(JadwalKhususDokter::class, 'id_jadwal_khusus_dokter');
    }

    public function poliklinik()
    {
        return $this->belongsTo(Poliklinik::class, 'id_poliklinik');
    }
}
