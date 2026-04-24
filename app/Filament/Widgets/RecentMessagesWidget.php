<?php

namespace App\Filament\Widgets;

use App\Models\Message;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMessagesWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Message::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone')
                    ->copyable()
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('listing.title')
                    ->label('Annonce')
                    ->limit(30)
                    ->default('N/A')
                    ->url(fn (Message $record): string => $record->listing 
                        ? \App\Filament\Resources\ListingResource::getUrl('edit', ['record' => $record->listing])
                        : '#')
                    ->openUrlInNewTab(),

                Tables\Columns\IconColumn::make('read_at')
                    ->label('Lu')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->formatStateUsing(fn ($state): string => $state ? $state->format('d/m/Y H:i') : '')
                    ->sortable(),
            ])
            ->heading('Derniers Messages')
            ->description('Les 5 messages les plus récents')
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Aucun message')
            ->emptyStateDescription('Les messages des visiteurs apparaîtront ici.')
            ->emptyStateIcon('heroicon-o-envelope');
    }
}
