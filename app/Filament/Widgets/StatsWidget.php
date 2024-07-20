<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Poliklinik;
use App\Models\Dokter;
use App\Models\Pasien;

class StatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('', Poliklinik::count())
                ->description('Jumlah total poliklinik')
                ->descriptionIcon('heroicon-o-heart')
                ->color('info'),
            Stat::make('', Dokter::count())
                ->description('Jumlah total dokter')
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('info'),
            Stat::make('', Pasien::count())
                ->description('Jumlah total pasien')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),
            Stat::make('', Pasien::whereNull('nomor_rm')->count())
                ->description('Jumlah pasien baru')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('success'),
        ];
    }
}