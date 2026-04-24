<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mes Favoris - AT Logement</title>
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
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-scale-in {
            animation: scaleIn 0.5s ease-out;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-orange-50/20 to-gray-50 min-h-screen">
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
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="hidden sm:inline">Mon Espace</span>
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header de la page -->
        <div class="mb-8 animate-fade-in-up">
            <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        Mes Favoris
                    </h1>
                    @if(!$favorites->isEmpty())
                        <p class="text-gray-600">
                            Vous avez <span class="font-bold text-[#f3a43e]">{{ $favorites->count() }}</span> annonce{{ $favorites->count() > 1 ? 's' : '' }} dans vos favoris
                        </p>
                    @endif
                </div>
                <a href="{{ route('home') }}" class="px-6 py-3 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-xl font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Parcourir les annonces
                </a>
            </div>
        </div>

        @if($favorites->isEmpty())
            <!-- État vide -->
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center animate-scale-in">
                <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun favori pour le moment</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Vous n'avez pas encore ajouté d'annonces à vos favoris. Commencez à explorer nos annonces et ajoutez celles qui vous intéressent !
                </p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-xl font-bold hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Parcourir les annonces
                </a>
            </div>
        @else
            <!-- Grille des favoris -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favorites as $index => $favorite)
                    @php
                        $listing = $favorite->listing;
                        if (!$listing) continue;
                        
                        // Récupérer la première image
                        $imageUrl = null;
                        if ($listing->images && is_array($listing->images) && count($listing->images) > 0) {
                            $firstImage = $listing->images[0];
                            if (str_starts_with($firstImage, 'http')) {
                                $imageUrl = $firstImage;
                            } else {
                                $imageUrl = asset('storage/' . $firstImage);
                            }
                        } else {
                            $imageUrl = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=2073';
                        }
                    @endphp
                    
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:scale-[1.02] transition-all duration-300 animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
                        <a href="{{ route('listings.show', $listing->slug) }}" class="block group">
                            <div class="relative h-56 bg-gray-200 overflow-hidden">
                                <img src="{{ $imageUrl }}" alt="{{ $listing->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute top-3 right-3">
                                    <span class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 shadow-lg">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        Favori
                                    </span>
                                </div>
                                @if($listing->price)
                                    <div class="absolute bottom-3 left-3 right-3">
                                        <div class="bg-white/95 backdrop-blur-sm rounded-lg px-4 py-2">
                                            <p class="text-xl font-bold text-[#f97316]">
                                                {{ number_format($listing->price, 0, ',', ' ') }} {{ $listing->currency ?? 'GNF' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#f3a43e] transition-colors duration-300">
                                    {{ $listing->title }}
                                </h3>
                                
                                @if($listing->address || $listing->city)
                                    <p class="text-sm text-gray-600 mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#f3a43e] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="line-clamp-1">{{ trim(($listing->address ?? '') . ($listing->address && $listing->city ? ', ' : '') . ($listing->city ?? '')) }}</span>
                                    </p>
                                @endif
                                
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Ajouté le {{ $favorite->created_at->format('d/m/Y') }}
                                    </span>
                                    <form action="{{ route('favorites.destroy', $listing->id) }}" method="POST" class="inline" onclick="event.preventDefault(); if(confirm('Voulez-vous retirer cette annonce de vos favoris ?')) { this.submit(); }">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold flex items-center gap-1 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-all duration-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Retirer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
