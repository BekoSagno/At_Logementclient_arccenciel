<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use Filament\Widgets\ChartWidget;

class ListingsStatusChart extends ChartWidget
{
    protected ?string $heading = 'Statut des Annonces';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $published = Listing::where('status', true)->count();
        $drafts = Listing::where('status', false)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Annonces',
                    'data' => [$published, $drafts],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.8)', // Vert pour publiées
                        'rgba(251, 191, 36, 0.8)', // Orange pour brouillons
                    ],
                    'borderColor' => [
                        'rgba(34, 197, 94, 1)',
                        'rgba(251, 191, 36, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Publiées', 'Brouillons'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
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
