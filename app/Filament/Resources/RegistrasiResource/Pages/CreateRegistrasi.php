<?php

namespace App\Filament\Resources\RegistrasiResource\Pages;

use App\Filament\Resources\RegistrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Carbon\Carbon;
use App\Models\JadwalDokter;
use App\Models\JadwalKhususDokter;

class CreateRegistrasi extends CreateRecord
{
    protected static string $resource = RegistrasiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $registrasi = $this->record;
        $tanggalKunjungan = $registrasi->tanggal_kunjungan;

        $jadwalKhusus = JadwalKhususDokter::where('id_dokter', $registrasi->id_dokter)
            ->where('tanggal', $tanggalKunjungan)
            ->first();

        if ($jadwalKhusus) {
            $jadwalKhusus->kuota = max(0, $jadwalKhusus->kuota - 1);
            $jadwalKhusus->save();
        } else {
            $hariKunjungan = Carbon::parse($tanggalKunjungan)->locale('id')->dayName;
            $jadwalDokter = JadwalDokter::where('id_dokter', $registrasi->id_dokter)
                ->where('hari', $hariKunjungan)
                ->first();

            if ($jadwalDokter) {
                $jadwalDokter->kuota = max(0, $jadwalDokter->kuota - 1);
                $jadwalDokter->save();
            }
        }
    }
}
