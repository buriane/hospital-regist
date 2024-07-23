<?php

namespace App\Filament\Resources\CutiDokterResource\Pages;

use App\Filament\Resources\CutiDokterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCutiDokter extends CreateRecord
{
    protected static string $resource = CutiDokterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
