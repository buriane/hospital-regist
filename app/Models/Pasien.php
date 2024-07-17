<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pasiens';
    protected $primaryKey = 'id_pasien';
    protected $guarded = ['id_pasien'];
    protected $fillable = [
        'nomor_rm',
        'nama_pasien',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'nomor_telepon',
        'email',
        'nomor_kartu',
    ];

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'id_pasien');
    }
}
