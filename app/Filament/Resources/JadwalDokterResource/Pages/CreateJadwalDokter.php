<?php

namespace App\Filament\Resources\JadwalDokterResource\Pages;

use App\Filament\Resources\JadwalDokterResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\JadwalDokter;
use Filament\Forms\Form;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class CreateJadwalDokter extends CreateRecord
{
    protected static string $resource = JadwalDokterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function form(Form $form): Form
    {
        return $form->schema(JadwalDokterResource::getCreateFormSchema());
    }

    protected function handleRecordCreation(array $data): JadwalDokter
    {
        $createdJadwal = null;
    
        foreach ($data['jadwal'] as $jadwalItem) {
            $jadwal = JadwalDokter::create([
                'id_dokter' => $data['id_dokter'],
                'hari' => $jadwalItem['hari'],
                'jam_mulai' => $jadwalItem['jam_mulai'],
                'jam_selesai' => $jadwalItem['jam_selesai'],
                'kuota' => $jadwalItem['kuota'],
            ]);

            if (!$createdJadwal) {
                $createdJadwal = $jadwal;
            }
        }
    
        if (!$createdJadwal) {
            Notification::make()
                ->danger()
                ->title('Gagal membuat jadwal dokter')
                ->body('Tidak ada jadwal yang dibuat. Pastikan setidaknya satu jadwal telah diisi.')
                ->send();

            $this->halt();
        }
    
        return $createdJadwal;
    }
}