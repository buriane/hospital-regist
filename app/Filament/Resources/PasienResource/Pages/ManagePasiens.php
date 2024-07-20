<?php

namespace App\Filament\Resources\PasienResource\Pages;

use App\Filament\Resources\PasienResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManagePasiens extends ManageRecords
{
    protected static string $resource = PasienResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Data Pasien';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Pasien'),
            'baru' => Tab::make('Pasien Baru')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('nomor_rm')),
            'lama' => Tab::make('Pasien Lama')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('nomor_rm')),
        ];
    }
}
