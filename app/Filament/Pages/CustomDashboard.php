<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminNotificationsWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RecentListingsWidget;
use App\Filament\Widgets\RecentMessagesWidget;
use App\Filament\Widgets\ListingsByTypeChart;
use App\Filament\Widgets\ListingsStatusChart;
use App\Filament\Widgets\MessagesChart;
use App\Filament\Widgets\ListingsByCityChart;
use App\Filament\Widgets\AveragePriceChart;
use Filament\Pages\Dashboard as BaseDashboard;

class CustomDashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Tableau de bord';

    protected static ?string $title = 'Tableau de bord';

    protected static ?int $navigationSort = -1;

    public function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            RecentListingsWidget::class,
            RecentMessagesWidget::class,
            ListingsByTypeChart::class,
            ListingsStatusChart::class,
            MessagesChart::class,
            ListingsByCityChart::class,
            AveragePriceChart::class,
        ];
    }
}
