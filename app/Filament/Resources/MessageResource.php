<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Models\Message;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Components\Grid as SchemaGrid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static ?string $navigationLabel = 'Messages';

    protected static ?string $modelLabel = 'Message';

    protected static ?string $pluralModelLabel = 'Messages';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                SchemaSection::make('Informations du message')
                    ->schema([
                        SchemaGrid::make(2)
                            ->schema([
                                Select::make('listing_id')
                                    ->label('Annonce concernée')
                                    ->relationship('listing', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                TextInput::make('name')
                                    ->label('Nom complet')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled(),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled(),

                                TextInput::make('phone')
                                    ->label('Téléphone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255)
                                    ->disabled(),

                                Textarea::make('message')
                                    ->label('Message')
                                    ->rows(6)
                                    ->disabled()
                                    ->columnSpanFull(),

                                DateTimePicker::make('read_at')
                                    ->label('Lu le')
                                    ->format('d/m/Y H:i')
                                    ->disabled(),
                            ]),
                    ]),
                SchemaSection::make('Réponse de l\'administrateur')
                    ->schema([
                        Textarea::make('admin_response')
                            ->label('Réponse')
                            ->rows(6)
                            ->placeholder('Tapez votre réponse ici...')
                            ->columnSpanFull()
                            ->helperText('Cette réponse sera envoyée par email au client.'),
                        
                        DateTimePicker::make('response_sent_at')
                            ->label('Réponse envoyée le')
                            ->format('d/m/Y H:i')
                            ->disabled()
                            ->visible(fn ($record) => $record && $record->response_sent_at !== null),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($record) => !$record || $record->admin_response === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('listing')->orderBy('created_at', 'desc'))
            ->defaultPaginationPageOption(25)
            ->columns([
                Tables\Columns\TextColumn::make('listing.title')
                    ->label('Annonce')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->default('N/A')
                    ->url(fn ($record) => $record->listing ? \App\Filament\Resources\ListingResource::getUrl('edit', ['record' => $record->listing]) : null)
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\IconColumn::make('read_at')
                    ->label('Lu')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                Tables\Columns\IconColumn::make('response_sent_at')
                    ->label('Répondu')
                    ->boolean()
                    ->trueIcon('heroicon-o-paper-airplane')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('info')
                    ->falseColor('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->formatStateUsing(fn ($state): string => $state ? $state->format('d/m/Y H:i') : '')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('read_at')
                    ->label('Statut de lecture')
                    ->placeholder('Tous les messages')
                    ->trueLabel('Messages lus')
                    ->falseLabel('Messages non lus')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('read_at'),
                        false: fn (Builder $query) => $query->whereNull('read_at'),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessages::route('/'),
            'view' => Pages\ViewMessage::route('/{record}'),
        ];
    }
}

