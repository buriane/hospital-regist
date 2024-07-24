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
        $startDate = Carbon::parse($data['tanggal_mulai']);
        $endDate = Carbon::parse($data['tanggal_akhir']);
        $createdJadwal = null;
    
        foreach ($data['jadwal'] as $jadwalItem) {
            $dayOfWeek = array_search($jadwalItem['hari'], ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $currentDate = $startDate->copy();
    
            while ($currentDate <= $endDate) {
                if ($currentDate->dayOfWeek === $dayOfWeek) {
                    $jadwal = JadwalDokter::create([
                        'id_dokter' => $data['id_dokter'],
                        'tanggal' => $currentDate->toDateString(),
                        'hari' => $jadwalItem['hari'],
                        'jam_mulai' => $jadwalItem['jam_mulai'],
                        'jam_selesai' => $jadwalItem['jam_selesai'],
                        'kuota' => $jadwalItem['kuota'],
                    ]);
    
                    if (!$createdJadwal) {
                        $createdJadwal = $jadwal;
                    }
                }
                $currentDate->addDay();
            }
        }
    
        if (!$createdJadwal) {
            Notification::make()
                ->danger()
                ->title('Gagal membuat jadwal dokter')
                ->body('Tidak ada jadwal yang dibuat. Pastikan hari yang dipilih berada dalam rentang tanggal yang ditentukan.')
                ->send();

            $this->halt();
        }
    
        return $createdJadwal;
    }
}