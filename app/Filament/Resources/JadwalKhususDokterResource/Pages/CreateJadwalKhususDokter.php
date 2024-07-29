<?php

namespace App\Filament\Resources\JadwalKhususDokterResource\Pages;

use App\Filament\Resources\JadwalKhususDokterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\JadwalKhususDokter;
use Filament\Forms\Form;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class CreateJadwalKhususDokter extends CreateRecord
{
    protected static string $resource = JadwalKhususDokterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function form(Form $form): Form
    {
        return $form->schema(JadwalKhususDokterResource::getCreateFormSchema());
    }

    protected function handleRecordCreation(array $data): JadwalKhususDokter
    {
        $createdJadwalKhusus = null;

        foreach ($data['jadwal_khusus'] as $jadwalKhususItem) {
            $jadwalKhusus = JadwalKhususDokter::create([
                'id_dokter' => $data['id_dokter'],
                'tanggal' => $jadwalKhususItem['tanggal'],
                'jam_mulai' => $jadwalKhususItem['jam_mulai'],
                'jam_selesai' => $jadwalKhususItem['jam_selesai'],
                'kuota' => $jadwalKhususItem['kuota'],
            ]);
            if (!$createdJadwalKhusus) {
                $createdJadwalKhusus = $jadwalKhusus;
            }
        }

        if (!$createdJadwalKhusus) {
            Notification::make()
                ->danger()
                ->title('Gagal membuat jadwal khusus dokter')
                ->body('Tidak ada jadwal khusus yang dibuat. Pastikan setidaknya satu jadwal khusus telah diisi.')
                ->send();
            $this->halt();
        }

        return $createdJadwalKhusus;
    }
}
