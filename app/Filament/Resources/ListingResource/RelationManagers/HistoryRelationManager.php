<?php

namespace App\Filament\Resources\ListingResource\RelationManagers;

use App\Models\ListingHistory;
use Filament\Actions;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class HistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'history';

    protected static ?string $title = 'Historique des modifications';

    protected static ?string $label = 'Historique';

    protected static ?string $pluralLabel = 'Historique';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Formulaire en lecture seule pour l'historique
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('action')
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        'restored' => 'warning',
                        'force_deleted' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Créé',
                        'updated' => 'Modifié',
                        'deleted' => 'Supprimé',
                        'restored' => 'Restauré',
                        'force_deleted' => 'Supprimé définitivement',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->default('Système')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('changes')
                    ->label('Changements')
                    ->limit(50)
                    ->tooltip(fn (ListingHistory $record): ?string => $record->changes)
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label('Action')
                    ->options([
                        'created' => 'Créé',
                        'updated' => 'Modifié',
                        'deleted' => 'Supprimé',
                        'restored' => 'Restauré',
                        'force_deleted' => 'Supprimé définitivement',
                    ])
                    ->native(false),
            ])
            ->defaultSort('created_at', 'desc')
            ->heading('Historique des modifications')
            ->description('Consultez l\'historique complet des modifications apportées à cette annonce')
            ->emptyStateHeading('Aucun historique')
            ->emptyStateDescription('L\'historique des modifications apparaîtra ici.')
            ->actions([
                Actions\ViewAction::make()
                    ->modalHeading(fn (ListingHistory $record) => 'Détails de la modification - ' . $record->created_at->format('d/m/Y H:i'))
                    ->modalContent(function (ListingHistory $record) {
                        return view('filament.resources.listing-resource.relation-managers.history-details', [
                            'record' => $record,
                        ]);
                    })
                    ->modalWidth('lg'),
            ])
            ->bulkActions([
                // Pas d'actions en masse pour l'historique
            ]);
    }
}
