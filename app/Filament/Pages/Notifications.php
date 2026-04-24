<?php

namespace App\Filament\Pages;

use App\Models\AdminNotification;
use Filament\Actions;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class Notifications extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationLabel = 'Notifications';
    
    protected static ?string $title = 'Notifications';
    
    protected static ?int $navigationSort = 3; // Après Messages (2)
    
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-bell';
    }
    
    protected string $view = 'filament.pages.notifications';
    
    public function getUnreadCount(): int
    {
        return AdminNotification::unread()->count();
    }
    
    public static function getNavigationBadge(): ?string
    {
        $count = AdminNotification::unread()->count();
        return $count > 0 ? (string) $count : null;
    }
    
    public function markAllAsRead(): void
    {
        AdminNotification::unread()->update([
            'read' => true,
            'read_at' => now(),
        ]);
        
        $this->dispatch('$refresh');
        // Déclencher la mise à jour du badge dans le header
        $this->dispatch('notification-badge-updated');
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(AdminNotification::query()->latest())
            ->columns([
                Tables\Columns\IconColumn::make('read')
                    ->label('Statut')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-bell')
                    ->trueColor('success')
                    ->falseColor('primary')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new_message' => 'primary',
                        'new_listing' => 'success',
                        'message_response' => 'info',
                        'listing_published' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new_message' => 'Nouveau message',
                        'new_listing' => 'Nouvelle annonce',
                        'message_response' => 'Réponse envoyée',
                        'listing_published' => 'Annonce publiée',
                        default => $state,
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->weight('bold')
                    ->limit(50),
                    
                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->limit(60)
                    ->wrap()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('read')
                    ->label('Statut')
                    ->placeholder('Toutes les notifications')
                    ->trueLabel('Lues')
                    ->falseLabel('Non lues')
                    ->queries(
                        true: fn (Builder $query) => $query->where('read', true),
                        false: fn (Builder $query) => $query->where('read', false),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->actions([
                Actions\Action::make('markAsRead')
                    ->label('Marquer comme lu')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (AdminNotification $record): bool => !$record->read)
                    ->action(function (AdminNotification $record) {
                        $record->markAsRead();
                        $this->dispatch('$refresh');
                        // Déclencher la mise à jour du badge dans le header
                        $this->dispatch('notification-badge-updated');
                    }),
                    
                Actions\Action::make('view')
                    ->label('Voir')
                    ->icon('heroicon-o-eye')
                    ->url(fn (AdminNotification $record): string => $record->action_url ?? '#')
                    ->openUrlInNewTab(),
                    
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkAction::make('markAsRead')
                    ->label('Marquer comme lu')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->markAsRead();
                        }
                        // Déclencher la mise à jour du badge dans le header
                        $this->dispatch('notification-badge-updated');
                    })
                    ->deselectRecordsAfterCompletion(),
                    
                Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('5s'); // Rafraîchissement automatique toutes les 5 secondes
    }
}
