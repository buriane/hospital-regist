<?php

namespace App\Filament\Resources\JadwalDokterResource\Pages;

use App\Filament\Resources\JadwalDokterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListJadwalDokters extends ListRecords
{
    protected static string $resource = JadwalDokterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Data Jadwal Dokter';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'senin' => Tab::make('Senin')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('hari', 'Senin')),
            'selasa' => Tab::make('Selasa')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('hari', 'Selasa')),
            'rabu' => Tab::make('Rabu')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('hari', 'Rabu')),
            'kamis' => Tab::make('Kamis')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('hari', 'Kamis')),
            'jumat' => Tab::make('Jumat')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('hari', 'Jumat')),
            'sabtu' => Tab::make('Sabtu')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('hari', 'Sabtu')),
            'minggu' => Tab::make('Minggu')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('hari', 'Minggu')),
        ];
    }
}
