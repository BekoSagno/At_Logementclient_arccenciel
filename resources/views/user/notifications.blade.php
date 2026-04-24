<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Notifications - AT Logement</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-orange-50/20 to-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50 backdrop-blur-sm bg-white/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#f3a43e] to-[#f97316] rounded-lg flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900 hidden sm:block">AT Logement</span>
                    </a>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-300">
                        Mon Espace
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-300">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 animate-fade-in-up">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#f3a43e] to-[#f97316] rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        Mes Notifications
                    </h1>
                    <p class="text-gray-600">{{ $notifications->total() }} notification{{ $notifications->total() > 1 ? 's' : '' }}</p>
                </div>
                @if($notifications->where('read', false)->count() > 0)
                    <button type="button" 
                            onclick="markAllAsRead()" 
                            class="px-6 py-3 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-lg font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        Tout marquer comme lu
                    </button>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 {{ !$notification->read ? 'border-l-4 border-[#f3a43e]' : '' }} animate-fade-in-up">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-[#f3a43e] to-[#f97316] rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900 mb-1">{{ $notification->title }}</h3>
                                    <p class="text-gray-700 mb-2">{{ $notification->message }}</p>
                                    <p class="text-xs text-gray-500">{{ $notification->created_at->format('d/m/Y à H:i') }}</p>
                                    
                                    @if($notification->type === 'new_listing' && isset($notification->data['listing_slug']))
                                        <a href="{{ route('listings.show', $notification->data['listing_slug']) }}" 
                                           class="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300 text-sm">
                                            <span>Voir l'annonce</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                                @if(!$notification->read)
                                    <button type="button" 
                                            onclick="markAsRead({{ $notification->id }})" 
                                            class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors">
                                        Marquer comme lu
                                    </button>
                                @else
                                    <span class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-sm font-semibold flex-shrink-0">
                                        Lu
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center animate-fade-in-up">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune notification</h3>
                    <p class="text-gray-600 mb-8">Vous n'avez pas encore de notifications.</p>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-xl font-bold hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        Retour au dashboard
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </main>

    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Recharger la page pour mettre à jour l'affichage
                    location.reload();
                } else {
                    console.error('Erreur lors du marquage de la notification');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }

        function markAllAsRead() {
            fetch('{{ route('notifications.read-all') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Recharger la page pour mettre à jour l'affichage
                    location.reload();
                } else {
                    console.error('Erreur lors du marquage de toutes les notifications');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }
    </script>
</body>
</html>
