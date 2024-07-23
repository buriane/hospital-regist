<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class LiveClockWidget extends Widget
{
    protected static string $view = 'filament.widgets.live-clock-widget';

    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    public function getViewData(): array
    {
        return [
            'currentTime' => now()->format('Y-m-d H:i:s'),
        ];
    }
}