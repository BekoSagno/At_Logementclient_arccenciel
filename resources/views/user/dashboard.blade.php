<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mon Espace - AT Logement</title>
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
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out;
        }
        
        .animate-scale-in {
            animation: scaleIn 0.5s ease-out;
        }
        
        .animate-pulse-slow {
            animation: pulse 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-orange-50/20 to-gray-50 min-h-screen">
    <script>
        function dashboardData() {
            return {
                activeTab: 'overview',
                notificationCount: 0,
                showNotifications: false,
                notifications: [],
                fetchNotifications() {
                    fetch('{{ route('api.notifications.unread') }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.notificationCount = data.count || 0;
                        this.notifications = data.notifications || [];
                    })
                    .catch(error => console.error('Erreur:', error));
                },
                checkNewResponses() {
                    // Vérifier s'il y a de nouvelles réponses
                    fetch('{{ route('api.messages.check-responses') }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.hasNewResponses) {
                            // Recharger la page pour afficher les nouvelles réponses
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
                },
                markAsRead(id) {
                    fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(() => this.fetchNotifications());
                },
                markAllAsRead() {
                    fetch('{{ route('notifications.read-all') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(() => this.fetchNotifications());
                },
                init() {
                    this.fetchNotifications();
                    // Vérifier les nouvelles réponses toutes les 10 secondes (temps réel)
                    setInterval(() => {
                        this.checkNewResponses();
                    }, 10000);
                    // Vérifier aussi les notifications toutes les 10 secondes (temps réel)
                    setInterval(() => {
                        this.fetchNotifications();
                    }, 10000);
                }
            };
        }
    </script>
    <div x-data="dashboardData()" class="min-h-screen">
        <!-- Header avec navigation -->
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
                        <div class="hidden md:flex items-center gap-1 bg-gray-100 rounded-lg p-1">
                            <button @click="activeTab = 'overview'" 
                                    :class="activeTab === 'overview' ? 'bg-white text-[#f3a43e] shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                                    class="px-4 py-2 rounded-md text-sm font-semibold transition-all duration-300">
                                Vue d'ensemble
                            </button>
                            <a href="{{ route('dashboard.favorites') }}" 
                                    class="px-4 py-2 rounded-md text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-white transition-all duration-300">
                                Mes Favoris
                            </a>
                            <a href="{{ route('profile.index') }}" class="px-4 py-2 rounded-md text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-white transition-all duration-300">
                                Mon Compte
                            </a>
                            <a href="{{ route('notifications.index') }}" class="px-4 py-2 rounded-md text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-white transition-all duration-300 relative">
                                Notifications
                                <span x-show="notificationCount > 0" x-cloak class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center animate-pulse" x-text="notificationCount"></span>
                            </a>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
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
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Vue d'ensemble -->
            <div x-show="activeTab === 'overview'" x-cloak class="space-y-6">
                <!-- Bienvenue -->
                <div class="bg-gradient-to-r from-[#f3a43e] to-[#f97316] rounded-2xl p-6 sm:p-8 text-white shadow-xl animate-fade-in-up">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold mb-2">Bienvenue, {{ auth()->user()->name }} ! 👋</h1>
                            <p class="text-orange-100">Gérez vos demandes et suivez vos annonces favorites</p>
                        </div>
                        <a href="{{ route('home') }}" class="px-6 py-3 bg-white text-[#f97316] rounded-xl font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Parcourir les annonces
                        </a>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 animate-fade-in-up" style="animation-delay: 0.1s">
                    <!-- Total des demandes -->
                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 border-l-4 border-[#f3a43e]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#f3a43e] to-[#f97316] rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_messages'] }}</div>
                        <div class="text-sm text-gray-600">Total des demandes</div>
                    </div>

                    <!-- Annonces consultées -->
                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 border-l-4 border-[#86c14f]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#86c14f] to-[#87c04f] rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['active_requests'] }}</div>
                        <div class="text-sm text-gray-600">Annonces consultées</div>
                    </div>

                    <!-- Réponses reçues -->
                    <div class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['read_responses'] }}</div>
                        <div class="text-sm text-gray-600">Réponses reçues</div>
                    </div>

                    <!-- Mes Favoris -->
                    <a href="{{ route('dashboard.favorites') }}" class="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 border-l-4 border-red-500 block">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total_favorites'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600 flex items-center justify-between">
                            <span>Mes Favoris</span>
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                </div>

                <!-- Annonces suivies -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#f3a43e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Mes Annonces Suivies
                        </h2>
                    </div>
                    <div class="p-6">
                        @forelse($interactedListings as $listing)
                            <div class="border-b border-gray-200 py-6 last:border-b-0 hover:bg-gray-50 transition-colors duration-300 rounded-lg px-4 -mx-4 animate-slide-in-right">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg text-gray-900 mb-2">
                                            <a href="{{ route('listings.show', $listing->slug) }}" 
                                               class="hover:text-[#f3a43e] transition-colors duration-300">
                                                {{ $listing->title }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-4 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ $listing->city }} - {{ $listing->address }}
                                        </p>
                                        
                                        <!-- Messages -->
                                        <div class="space-y-3">
                                            @foreach($listing->messages->where('user_id', auth()->id()) as $message)
                                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-4 border-l-4 {{ $message->read_at ? 'border-[#86c14f]' : 'border-yellow-400' }}">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-xs text-gray-500 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            {{ $message->created_at->format('d/m/Y à H:i') }}
                                                        </span>
                                                        @if($message->read_at)
                                                            <span class="text-xs bg-[#86c14f] text-white px-3 py-1 rounded-full font-semibold flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                Répondu
                                                            </span>
                                                        @else
                                                            <span class="text-xs bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full font-semibold flex items-center gap-1 animate-pulse-slow">
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                En attente
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-gray-700">{{ $message->message ?? 'Aucun message' }}</p>
                                                    
                                                    <!-- Afficher la réponse de l'admin si elle existe -->
                                                    @if($message->admin_response)
                                                        <div class="mt-3 pt-3 border-t border-gray-300">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <svg class="w-4 h-4 text-[#f3a43e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="text-xs font-semibold text-[#f3a43e]">Réponse de l'administrateur</span>
                                                                <span class="text-xs text-gray-500">({{ $message->response_sent_at->format('d/m/Y à H:i') }})</span>
                                                            </div>
                                                            <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-3 border-l-4 border-[#f3a43e]">
                                                                <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $message->admin_response }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('listings.show', $listing->slug) }}" 
                                       class="px-6 py-3 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap">
                                        <span>Voir l'annonce</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune annonce suivie</h3>
                                <p class="text-gray-600 mb-6">Vous n'avez pas encore interagi avec des annonces.</p>
                                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-lg font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Parcourir les annonces
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Historique des messages -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#f3a43e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Historique de mes Messages
                        </h2>
                    </div>
                    <div class="p-6">
                        @forelse($messages as $message)
                            <div class="border-b border-gray-200 py-4 last:border-b-0 hover:bg-gray-50 transition-colors duration-300 rounded-lg px-4 -mx-4 animate-slide-in-right">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        @if($message->listing)
                                            <h4 class="font-bold text-gray-900 mb-1">
                                                <a href="{{ route('listings.show', $message->listing->slug) }}" class="hover:text-[#f3a43e] transition-colors duration-300">
                                                    {{ $message->listing->title }}
                                                </a>
                                            </h4>
                                        @else
                                            <h4 class="font-bold text-gray-900 mb-1">Message général</h4>
                                        @endif
                                        <p class="text-xs text-gray-500 mb-2 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message->created_at->format('d/m/Y à H:i') }}
                                        </p>
                                        <p class="text-sm text-gray-700">{{ $message->message ?? 'Aucun message' }}</p>
                                        
                                        <!-- Afficher la réponse de l'admin si elle existe -->
                                        @if($message->admin_response)
                                            <div class="mt-4 pt-4 border-t border-gray-300">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <svg class="w-4 h-4 text-[#f3a43e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-xs font-semibold text-[#f3a43e]">Réponse de l'administrateur</span>
                                                    @if($message->response_sent_at)
                                                        <span class="text-xs text-gray-500">({{ $message->response_sent_at->format('d/m/Y à H:i') }})</span>
                                                    @endif
                                                </div>
                                                <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-4 border-l-4 border-[#f3a43e]">
                                                    <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $message->admin_response }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if($message->admin_response)
                                            <span class="text-xs bg-[#86c14f] text-white px-3 py-1 rounded-full font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Répondu
                                            </span>
                                        @elseif($message->read_at)
                                            <span class="text-xs bg-blue-500 text-white px-3 py-1 rounded-full font-semibold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Lu
                                            </span>
                                        @else
                                            <span class="text-xs bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full font-semibold flex items-center gap-1 animate-pulse-slow">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                En attente
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500">Aucun message pour le moment.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>
</html>
