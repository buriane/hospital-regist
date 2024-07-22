<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use App\Models\Registrasi;

class WeeklyRegistrationChart extends ChartWidget
{
    protected static ?string $heading = 'Registrasi Minggu Ini';

    protected function getData(): array
    {
        $startDate = now()->startOfWeek();
        $endDate = now()->endOfWeek();

        $dailyCounts = Registrasi::select(DB::raw('DATE(tanggal_kunjungan) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('tanggal_kunjungan', [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $period = CarbonPeriod::create($startDate, $endDate);
        $labels = [];
        $data = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $labels[] = $date->format('D'); // Short day name
            $data[] = $dailyCounts[$formattedDate] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Registrasi',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}