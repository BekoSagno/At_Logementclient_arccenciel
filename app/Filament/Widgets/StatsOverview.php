<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ListingResource;
use App\Filament\Resources\MessageResource;
use App\Models\Listing;
use App\Models\Message;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalListings = Listing::count();
        $publishedListings = Listing::where('status', true)->count();
        $draftListings = Listing::where('status', false)->count();
        $unreadMessages = Message::whereNull('read_at')->count();
        $totalMessages = Message::count();
        $featuredListings = Listing::where('is_featured', true)->count();

        return [
            Stat::make('Total Annonces', $totalListings)
                ->description('Toutes les annonces')
                ->descriptionIcon('heroicon-m-home')
                ->color('success') // Vert #86c14f
                ->chart([7, 3, 4, 5, 6, 3, 5])
                ->url(ListingResource::getUrl('index'))
                ->icon('heroicon-o-home-modern'),

            Stat::make('Annonces Publiées', $publishedListings)
                ->description('Visibles sur le site')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success') // Vert #86c14f
                ->chart([3, 2, 4, 3, 5, 4, 6])
                ->url(ListingResource::getUrl('index') . '?tableFilters[status]=1')
                ->icon('heroicon-o-check-badge'),

            Stat::make('Brouillons', $draftListings)
                ->description('Non publiées')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning') // Orange #f3a43e
                ->chart([2, 1, 2, 1, 1, 2, 1])
                ->url(ListingResource::getUrl('index') . '?tableFilters[status]=0')
                ->icon('heroicon-o-document-text'),

            Stat::make('Mises en Avant', $featuredListings)
                ->description('Annonces prioritaires')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary') // Orange principal #f97316
                ->chart([1, 2, 1, 3, 2, 1, 2])
                ->url(ListingResource::getUrl('index') . '?tableFilters[is_featured]=1')
                ->icon('heroicon-o-sparkles'),

            Stat::make('Messages Non Lus', $unreadMessages)
                ->description("Sur $totalMessages messages")
                ->descriptionIcon('heroicon-m-envelope')
                ->color($unreadMessages > 0 ? 'danger' : 'success')
                ->chart([2, 3, 2, 4, 3, 2, 3])
                ->url(MessageResource::getUrl('index') . '?tableFilters[read_at]=0')
                ->icon('heroicon-o-envelope'),
        ];
    }
}
