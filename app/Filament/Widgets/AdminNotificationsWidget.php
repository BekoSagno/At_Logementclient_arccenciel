<?php

namespace App\Filament\Widgets;

use App\Models\AdminNotification;
use Filament\Widgets\Widget;
use Livewire\Attributes\On;

class AdminNotificationsWidget extends Widget
{
    protected string $view = 'filament.widgets.admin-notifications-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    public ?string $pollingInterval = '5s'; // Rafraîchissement automatique toutes les 5 secondes
    
    public function getViewData(): array
    {
        $notifications = AdminNotification::unread()
            ->recent(10)
            ->get();
            
        $unreadCount = AdminNotification::unread()->count();
        
        return [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ];
    }
    
    public function markAsRead($notificationId)
    {
        $notification = AdminNotification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('$refresh');
        }
    }
    
    public function markAllAsRead()
    {
        AdminNotification::unread()->update([
            'read' => true,
            'read_at' => now(),
        ]);
        $this->dispatch('$refresh');
    }
    
    #[On('notification-created')]
    public function refreshNotifications()
    {
        $this->dispatch('$refresh');
    }
}
