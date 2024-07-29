<?php

namespace App\Filament\Resources\JadwalKhususDokterResource\Pages;

use App\Filament\Resources\JadwalKhususDokterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalKhususDokter extends EditRecord
{
    protected static string $resource = JadwalKhususDokterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
