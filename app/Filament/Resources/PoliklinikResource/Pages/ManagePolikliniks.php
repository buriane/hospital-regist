<?php

namespace App\Filament\Resources\PoliklinikResource\Pages;

use App\Filament\Resources\PoliklinikResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePolikliniks extends ManageRecords
{
    protected static string $resource = PoliklinikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Data Poliklinik';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
