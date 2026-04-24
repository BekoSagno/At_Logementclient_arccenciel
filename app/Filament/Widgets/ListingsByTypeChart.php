<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use Filament\Widgets\ChartWidget;

class ListingsByTypeChart extends ChartWidget
{
    protected ?string $heading = 'Répartition des Annonces par Type';

    protected static ?int $sort = 2;

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
        $colors = [
            'rgba(34, 197, 94, 0.8)', // Vert
            'rgba(251, 191, 36, 0.8)', // Orange
            'rgba(59, 130, 246, 0.8)', // Bleu
            'rgba(168, 85, 247, 0.8)', // Violet
        ];

        foreach ($types as $key => $label) {
            $count = Listing::where('type', $key)->count();
            $data[] = $count;
            $labels[] = $label;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Nombre d\'annonces',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => array_map(fn($color) => str_replace('0.8', '1', $color), $colors),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
