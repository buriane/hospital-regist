<?php

namespace App\Filament\Resources\JadwalKhususDokterResource\Pages;

use App\Filament\Resources\JadwalKhususDokterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListJadwalKhususDokters extends ListRecords
{
    protected static string $resource = JadwalKhususDokterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Data Jadwal Khusus Dokter';
    }
}
