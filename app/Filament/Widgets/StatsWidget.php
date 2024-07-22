<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Poliklinik;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Registrasi;
use Carbon\Carbon;

class StatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $getRegistrationStats = function($date) {
            $registrations = Registrasi::whereDate('tanggal_kunjungan', $date);
            $total = $registrations->count();
            $confirmed = Registrasi::whereDate('tanggal_kunjungan', $date)->where('status', 'Confirmed')->count();
            $canceled = Registrasi::whereDate('tanggal_kunjungan', $date)->where('status', 'Canceled')->count();
            $pending = Registrasi::whereDate('tanggal_kunjungan', $date)->where('status', 'Pending')->count();
            
            return [
                'total' => $total,
                'confirmed' => $confirmed,
                'canceled' => $canceled,
                'pending' => $pending,
            ];
        };

        $todayStats = $getRegistrationStats($today);
        $tomorrowTotal = Registrasi::whereDate('tanggal_kunjungan', $tomorrow)->count();

        $weeklyRegistrations = Registrasi::whereBetween('tanggal_kunjungan', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        $confirmationRatio = $todayStats['total'] > 0 
            ? round(($todayStats['confirmed'] / $todayStats['total']) * 100, 2) 
            : 0;

        return [
            Stat::make("Registrasi Hari Ini", $todayStats['total'])
                ->description("Terkonfirmasi: {$todayStats['confirmed']} | Dibatalkan: {$todayStats['canceled']} | Pending: {$todayStats['pending']}")
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('primary'),
            Stat::make("Rasio Konfirmasi", "{$confirmationRatio}%")
                ->description("Persentase registrasi terkonfirmasi hari ini")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Total Dokter', Dokter::count())
                ->description('Jumlah total dokter')
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('info'),
            Stat::make('Total Pasien', Pasien::count())
                ->description('Jumlah total pasien')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),
            Stat::make('Pasien Baru', Pasien::whereNull('nomor_rm')->count())
                ->description('Jumlah pasien baru')
                ->descriptionIcon('heroicon-o-user-plus')
                ->color('warning'),
            Stat::make('Total Poliklinik', Poliklinik::count())
                ->description('Jumlah total poliklinik')
                ->descriptionIcon('heroicon-o-heart')
                ->color('info'),
            Stat::make("Registrasi Besok", $tomorrowTotal)
                ->description("Total registrasi untuk besok")
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),
            Stat::make("Registrasi Minggu Ini", $weeklyRegistrations)
                ->description("Total registrasi minggu ini")
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('info'),
        ];
    }
}