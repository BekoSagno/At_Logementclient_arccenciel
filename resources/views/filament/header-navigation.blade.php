@php
    $currentUrl = request()->url();
    $currentPath = request()->path();
    // Dashboard si on est sur /admin ou /admin/ (sans autres segments)
    $isDashboard = $currentPath === 'admin' || $currentPath === 'admin/';
    $isListings = str_contains($currentUrl, '/admin/listings');
    $isMessages = str_contains($currentUrl, '/admin/messages');
    $isNotifications = str_contains($currentUrl, '/admin/notifications');
@endphp

<div class="fi-topbar-nav">
    <a href="{{ \App\Filament\Pages\CustomDashboard::getUrl() }}" 
       class="fi-topbar-nav-item dashboard-btn {{ $isDashboard ? 'active' : '' }}">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Tableau de bord
    </a>
    
    <a href="{{ \App\Filament\Resources\ListingResource::getUrl('index') }}" 
       class="fi-topbar-nav-item {{ $isListings ? 'active' : '' }}">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Annonces
    </a>
    
    <a href="{{ \App\Filament\Resources\MessageResource::getUrl('index') }}" 
       class="fi-topbar-nav-item {{ $isMessages ? 'active' : '' }}">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
        Messages
    </a>
    
    <a href="{{ \App\Filament\Pages\Notifications::getUrl() }}" 
       class="fi-topbar-nav-item {{ $isNotifications ? 'active' : '' }}">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        Notifications
        @php
            $unreadCount = \App\Models\AdminNotification::unread()->count();
        @endphp
        <span id="notification-badge-container" class="notification-badge-wrapper">
            @if($unreadCount > 0)
                <span class="notification-badge ml-2 px-2 py-0.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold rounded-full" data-count="{{ $unreadCount }}">
                    {{ $unreadCount }}
                </span>
            @endif
        </span>
    </a>
</div>

<style>
    .fi-topbar-nav {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        margin: 0 auto !important;
        flex: 1 !important;
    }

    .fi-topbar-nav-item {
        padding: 0.75rem 1.5rem !important;
        border-radius: 10px !important;
        font-weight: 500 !important;
        color: #f3a43e !important;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative !important;
        overflow: hidden !important;
        display: flex !important;
        align-items: center !important;
        background: linear-gradient(135deg, rgba(243, 164, 62, 0.08) 0%, rgba(243, 164, 62, 0.03) 100%) !important;
        border: 1px solid rgba(243, 164, 62, 0.15) !important;
    }

    .fi-topbar-nav-item::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background: #f3a43e;
        transition: width 0.3s ease;
    }

    .fi-topbar-nav-item:hover {
        background: linear-gradient(135deg, rgba(243, 164, 62, 0.15) 0%, rgba(243, 164, 62, 0.08) 100%) !important;
        color: #f3a43e !important;
        transform: translateY(-2px) !important;
        border-color: rgba(243, 164, 62, 0.3) !important;
        box-shadow: 0 4px 12px rgba(243, 164, 62, 0.2) !important;
    }

    .fi-topbar-nav-item:hover::before {
        width: 100%;
    }

    .fi-topbar-nav-item.active {
        background: linear-gradient(135deg, rgba(243, 164, 62, 0.2) 0%, rgba(243, 164, 62, 0.12) 100%) !important;
        color: #f3a43e !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 16px rgba(243, 164, 62, 0.25) !important;
        border-color: rgba(243, 164, 62, 0.4) !important;
    }

    .fi-topbar-nav-item.active::before {
        width: 100%;
    }

    /* Style spécial pour le bouton Tableau de bord */
    .fi-topbar-nav-item.dashboard-btn {
        background: linear-gradient(135deg, rgba(243, 164, 62, 0.12) 0%, rgba(243, 164, 62, 0.05) 100%) !important;
        border: 2px solid rgba(243, 164, 62, 0.25) !important;
        font-weight: 600 !important;
    }

    .fi-topbar-nav-item.dashboard-btn:hover {
        background: linear-gradient(135deg, rgba(243, 164, 62, 0.2) 0%, rgba(243, 164, 62, 0.12) 100%) !important;
        border-color: rgba(243, 164, 62, 0.4) !important;
    }

    .fi-topbar-nav-item.dashboard-btn.active {
        background: linear-gradient(135deg, rgba(243, 164, 62, 0.25) 0%, rgba(243, 164, 62, 0.15) 100%) !important;
        border-color: rgba(243, 164, 62, 0.5) !important;
    }

    /* Animation de clignotement ROUGE pour les notifications */
    .notification-badge {
        animation: blink-red 1.5s ease-in-out infinite !important;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.8) !important;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
    }

    @keyframes blink-red {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.8);
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        50% {
            opacity: 0.6;
            transform: scale(1.15);
            box-shadow: 0 0 25px rgba(239, 68, 68, 1);
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        }
    }

    /* Animation supplémentaire ROUGE pour le lien Notifications quand il y a des notifications */
    .fi-topbar-nav-item:has(.notification-badge) {
        animation: notification-pulse-red 2s ease-in-out infinite !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
    }

    @keyframes notification-pulse-red {
        0%, 100% {
            background: linear-gradient(135deg, rgba(243, 164, 62, 0.08) 0%, rgba(243, 164, 62, 0.03) 100%);
            border-color: rgba(243, 164, 62, 0.15);
            box-shadow: 0 0 0 rgba(239, 68, 68, 0);
        }
        50% {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.1) 100%);
            border-color: rgba(239, 68, 68, 0.5);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
        }
    }

    @media (max-width: 768px) {
        .fi-topbar-nav {
            margin: 0 auto !important;
            gap: 0.25rem !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
        }

        .fi-topbar-nav-item {
            padding: 0.5rem 1rem !important;
            font-size: 0.875rem !important;
        }

        .fi-topbar-nav-item svg {
            width: 1.25rem !important;
            height: 1.25rem !important;
            margin-right: 0.5rem !important;
        }
    }
</style>

<script>
    // Rafraîchissement automatique du badge de notifications
    (function() {
        let updateNotificationBadge = async function() {
            try {
                const response = await fetch('/admin/api/notifications/unread-count', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const count = data.count || 0;
                    const badgeContainer = document.getElementById('notification-badge-container');
                    
                    if (badgeContainer) {
                        if (count > 0) {
                            let badge = badgeContainer.querySelector('.notification-badge');
                            if (badge) {
                                badge.textContent = count;
                                badge.setAttribute('data-count', count);
                            } else {
                                badgeContainer.innerHTML = '<span class="notification-badge ml-2 px-2 py-0.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold rounded-full" data-count="' + count + '">' + count + '</span>';
                            }
                        } else {
                            badgeContainer.innerHTML = '';
                        }
                    }
                }
            } catch (error) {
                console.error('Erreur lors de la mise à jour du badge:', error);
            }
        };
        
        // Mettre à jour immédiatement
        updateNotificationBadge();
        
        // Mettre à jour toutes les 3 secondes
        setInterval(updateNotificationBadge, 3000);
        
        // Écouter les événements Livewire pour mettre à jour après les actions
        if (typeof Livewire !== 'undefined') {
            document.addEventListener('livewire:init', () => {
                Livewire.hook('morph.updated', () => {
                    setTimeout(updateNotificationBadge, 500);
                });
            });
            
            // Écouter l'événement personnalisé de mise à jour du badge
            Livewire.on('notification-badge-updated', () => {
                setTimeout(updateNotificationBadge, 300);
            });
        }
        
        // Écouter les événements de clic sur les actions de notification
        document.addEventListener('click', function(e) {
            if (e.target.closest('[wire\\:click*="markAsRead"]') || 
                e.target.closest('[wire\\:click*="markAllAsRead"]')) {
                setTimeout(updateNotificationBadge, 500);
            }
        });
        
        // Écouter les événements Livewire généraux
        document.addEventListener('livewire:navigated', () => {
            setTimeout(updateNotificationBadge, 300);
        });
    })();
</script>
