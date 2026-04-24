<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentListingsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Listing::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->size(50)
                    ->defaultImageUrl('/images/placeholder.png'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->limit(40)
                    ->weight('bold')
                    ->url(fn (Listing $record): string => \App\Filament\Resources\ListingResource::getUrl('edit', ['record' => $record])),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'residential' => 'success',
                        'commercial' => 'warning',
                        'land' => 'info',
                        'service' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'residential' => 'Résidentiel',
                        'commercial' => 'Commercial',
                        'land' => 'Terrain',
                        'service' => 'Service',
                        default => $state,
                    }),

                Tables\Columns\IconColumn::make('status')
                    ->label('Publié')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Mise en avant')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->formatStateUsing(fn ($state): string => $state ? $state->format('d/m/Y H:i') : '')
                    ->sortable(),
            ])
            ->heading('Dernières Annonces')
            ->description('Les 5 annonces les plus récentes')
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Aucune annonce')
            ->emptyStateDescription('Créez votre première annonce pour commencer.')
            ->emptyStateIcon('heroicon-o-home');
    }
}
