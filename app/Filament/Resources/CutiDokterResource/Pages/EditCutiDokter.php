<?php

namespace App\Filament\Resources\CutiDokterResource\Pages;

use App\Filament\Resources\CutiDokterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCutiDokter extends EditRecord
{
    protected static string $resource = CutiDokterResource::class;

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
