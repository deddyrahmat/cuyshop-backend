<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class SuccessOrderChart extends ChartWidget
{
    protected static ?string $heading = 'Order Success Chart ';
    protected static string $color = 'success';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '60s';
    protected function getData(): array
    {
        Carbon::setLocale('id');
        $data = Trend::query(Order::where('status', 'success'))
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Success',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    // 'backgroundColor' => '#36A2EB',
                    // 'borderColor' => '#9BD0F5',
                ],
            ],
            // Carbon::parse($value->date)->translatedFormat('F')
            'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->translatedFormat('F')),
        ];
    }
    protected function getType(): string
    {
        return 'line';
    }
}
