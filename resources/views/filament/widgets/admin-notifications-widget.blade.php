@php
    $notifications = $this->getViewData()['notifications'];
    $unreadCount = $this->getViewData()['unreadCount'];
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-bell" class="w-5 h-5" />
                    <span>Notifications</span>
                    @if($unreadCount > 0)
                        <span class="px-2 py-1 text-xs font-bold text-white bg-primary-600 rounded-full">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </div>
                @if($unreadCount > 0)
                    <x-filament::button 
                        size="sm" 
                        color="gray"
                        wire:click="markAllAsRead"
                    >
                        Tout marquer comme lu
                    </x-filament::button>
                @endif
            </div>
        </x-slot>

        <div class="space-y-2">
            @forelse($notifications as $notification)
                <div 
                    wire:key="notification-{{ $notification->id }}"
                    class="flex items-start gap-3 p-3 rounded-lg border transition-colors hover:bg-gray-50 {{ !$notification->read ? 'bg-primary-50 border-primary-200' : 'bg-white border-gray-200' }}"
                >
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-{{ $notification->color }}-100">
                            <x-filament::icon 
                                icon="{{ $notification->icon ?? 'heroicon-o-bell' }}" 
                                class="w-5 h-5 text-{{ $notification->color }}-600" 
                            />
                        </div>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $notification->title }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $notification->message }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            
                            @if(!$notification->read)
                                <button
                                    wire:click="markAsRead({{ $notification->id }})"
                                    class="flex-shrink-0 p-1 text-gray-400 hover:text-gray-600 transition-colors"
                                    title="Marquer comme lu"
                                >
                                    <x-filament::icon icon="heroicon-o-x-mark" class="w-4 h-4" />
                                </button>
                            @endif
                        </div>
                        
                        @if($notification->action_url)
                            <div class="mt-2">
                                <a 
                                    href="{{ $notification->action_url }}"
                                    class="text-xs font-medium text-primary-600 hover:text-primary-700 inline-flex items-center gap-1"
                                    wire:navigate
                                >
                                    Voir les détails
                                    <x-filament::icon icon="heroicon-o-arrow-right" class="w-3 h-3" />
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <x-filament::icon icon="heroicon-o-bell-slash" class="w-12 h-12 text-gray-400 mx-auto mb-2" />
                    <p class="text-sm text-gray-500">Aucune notification</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
