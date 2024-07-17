<?php

namespace App\Filament\Resources\RegistrasiResource\Pages;

use App\Filament\Resources\RegistrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrasis extends ListRecords
{
    protected static string $resource = RegistrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Data Registrasi';
    }
}
