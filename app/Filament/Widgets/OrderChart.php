<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderChart extends ChartWidget
{
    protected static ?string $heading = 'Order Chart';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        function getChartOrderByStatus(string $label)
        {
            $data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            Order::whereYear('created_at', now()->year)
                ->where('status', $label)
                ->selectRaw('MONTH(created_at) as bulan, SUM(total) as total_biaya')
                ->groupBy('bulan')
                ->get()
                ->each(function ($item) use (&$data) {
                    $data[$item->bulan - 1] = $item->total_biaya;
                });

            return $data;
        }


        $status = [
            [
                'label' => 'new',
                'borderColor' => '#3b82f6',
                'active' => true
            ],
            [
                'label' => 'cancelled',
                'borderColor' => '#ef4444',
                'active' => false
            ],
            [
                'label' => 'processing',
                'borderColor' => '#f59e0b',
                'active' => false
            ],
            [
                'label' => 'shipped',
                'borderColor' => '#22c55e',
                'active' => false
            ],
            [
                'label' => 'delivered',
                'borderColor' => '#22c55e',
                'active' => true
            ],
        ];

        $datasets = [];

        foreach ($status as $stat) {
            $datasets[] = [
                'label' => $stat['label'],
                'data' => getChartOrderByStatus($stat['label']),
                'borderColor' => $stat['borderColor']
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
