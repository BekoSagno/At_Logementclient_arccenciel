<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use Filament\Widgets\ChartWidget;

class AveragePriceChart extends ChartWidget
{
    protected ?string $heading = 'Prix Moyen par Type d\'Annonce';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $types = [
            'residential' => 'Résidentiel',
            'commercial' => 'Commercial',
            'land' => 'Terrain',
            'service' => 'Service',
        ];

        $data = [];
        $labels = [];

        foreach ($types as $key => $label) {
            $avgPrice = Listing::where('type', $key)
                ->whereNotNull('price')
                ->avg('price');
            
            // Convertir en millions de GNF pour meilleure lisibilité
            $avgPriceInMillions = $avgPrice ? round($avgPrice / 1000000, 2) : 0;
            
            $data[] = $avgPriceInMillions;
            $labels[] = $label;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Prix moyen (millions GNF)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.6)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
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
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => "function(context) {
                            return 'Prix moyen: ' + context.parsed.y.toLocaleString('fr-FR') + ' millions GNF';
                        }",
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => "function(value) {
                            return value.toLocaleString('fr-FR') + 'M';
                        }",
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
