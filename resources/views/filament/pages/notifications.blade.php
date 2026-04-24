<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Notifications
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Gérez toutes vos notifications ici
                </p>
            </div>
            
            @if($this->getUnreadCount() > 0)
                <x-filament::button 
                    color="primary"
                    wire:click="markAllAsRead"
                >
                    Tout marquer comme lu
                </x-filament::button>
            @endif
        </div>
        
        {{ $this->table }}
    </div>
</x-filament-panels::page>
