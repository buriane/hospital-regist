<?php

namespace App\Filament\Resources\CutiDokterResource\Pages;

use App\Filament\Resources\CutiDokterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCutiDokters extends ListRecords
{
    protected static string $resource = CutiDokterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Data Cuti Dokter';
    }
}
