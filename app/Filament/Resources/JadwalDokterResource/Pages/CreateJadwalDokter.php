<?php

namespace App\Filament\Resources\JadwalDokterResource\Pages;

use App\Filament\Resources\JadwalDokterResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\JadwalDokter;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Form;

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

    protected function handleRecordCreation(array $data): Model
    {
        $model = $this->getModel();
        return new $model();
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();
        $dokter_id = $data['id_dokter'];
        
        if (isset($data['jadwal']) && is_array($data['jadwal'])) {
            foreach ($data['jadwal'] as $jadwal) {
                if (
                    isset($jadwal['tanggal']) &&
                    isset($jadwal['hari']) &&
                    isset($jadwal['jam_mulai']) &&
                    isset($jadwal['jam_selesai']) &&
                    isset($jadwal['kuota'])
                ) {
                    JadwalDokter::create([
                        'id_dokter' => $dokter_id,
                        'tanggal' => $jadwal['tanggal'],
                        'hari' => $jadwal['hari'],
                        'jam_mulai' => $jadwal['jam_mulai'],
                        'jam_selesai' => $jadwal['jam_selesai'],
                        'kuota' => $jadwal['kuota'],
                    ]);
                }
            }
        }
    }
}