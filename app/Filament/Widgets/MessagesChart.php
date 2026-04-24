<?php

namespace App\Filament\Widgets;

use App\Models\Message;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MessagesChart extends ChartWidget
{
    protected ?string $heading = 'Messages Reçus (6 Derniers Mois)';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $months = [];
        $data = [];

        // Générer les 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->locale('fr')->translatedFormat('M Y');
            
            $count = Message::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Messages reçus',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
