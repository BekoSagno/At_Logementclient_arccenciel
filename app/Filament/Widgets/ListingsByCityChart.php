<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use Filament\Widgets\ChartWidget;

class ListingsByCityChart extends ChartWidget
{
    protected ?string $heading = 'Annonces par Ville';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Récupérer les villes avec le plus d'annonces (top 10)
        $cities = Listing::selectRaw('city, COUNT(*) as count')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->groupBy('city')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $labels = $cities->pluck('city')->toArray();
        $data = $cities->pluck('count')->toArray();

        // Générer des couleurs dynamiques
        $colors = [];
        $baseColors = [
            'rgba(34, 197, 94, 0.8)',
            'rgba(251, 191, 36, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(14, 165, 233, 0.8)',
            'rgba(251, 146, 60, 0.8)',
            'rgba(34, 197, 94, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(59, 130, 246, 0.8)',
        ];

        foreach ($data as $index => $value) {
            $colors[] = $baseColors[$index % count($baseColors)];
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
                'x' => [
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 45,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
