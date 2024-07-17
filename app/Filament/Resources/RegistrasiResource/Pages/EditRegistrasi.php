<?php

namespace App\Filament\Resources\RegistrasiResource\Pages;

use App\Filament\Resources\RegistrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistrasi extends EditRecord
{
    protected static string $resource = RegistrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
