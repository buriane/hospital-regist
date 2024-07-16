<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiDokter extends Model
{
    use HasFactory;
    protected $table = 'cuti_dokters';
    protected $primaryKey = 'id_cuti_dokter';
    protected $guarded = ['id_cuti_dokter'];
    protected $fillable = [
        'id_dokter',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter');
    }

    public function scopeActive($query)
    {
        return $query->where('tanggal_selesai', '>=', now());
    }

    public function isActive()
    {
        return $this->tanggal_selesai >= now();
    }
}
