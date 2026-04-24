<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $listing->title }} - AT Logement</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50" x-data="listingPageData()">
    <div class="min-h-screen">
        <x-header />
        
        <main class="pt-20 pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="inline-flex items-center text-orange-600 hover:text-orange-700 mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à l'accueil
                </a>
                
                @php
                    // Générer les URLs pour toutes les images
                    $imageUrls = [];
                    if ($listing->images && count($listing->images) > 0) {
                        foreach($listing->images as $img) {
                            $filePath = str_starts_with($img, 'listings/') ? $img : 'listings/' . $img;
                            if (!str_starts_with($img, 'http') && !str_starts_with($img, '/')) {
                                if (Storage::disk('local')->exists($filePath)) {
                                    $imageUrls[] = route('listing.image', ['path' => $img]);
                                } elseif (Storage::disk('public')->exists($img)) {
                                    $imageUrls[] = Storage::disk('public')->url($img);
                                } else {
                                    $imageUrls[] = $img;
                                }
                            } else {
                                $imageUrls[] = $img;
                            }
                        }
                    }
                    $currentImageUrl = $imageUrls[0] ?? '';
                @endphp
                
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden max-w-4xl mx-auto">
                    <!-- Images avec carousel -->
                    @if($listing->images && count($listing->images) > 0)
                        <div class="relative h-64 overflow-hidden bg-gray-100">
                            <img src="{{ $currentImageUrl }}" 
                                 alt="{{ $listing->title }}" 
                                 class="w-full h-full object-cover"
                                 id="listing-main-image">
                            
                            @if(count($listing->images) > 1)
                                <!-- Navigation images -->
                                <button onclick="previousImage()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2 transition-colors">
                                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2 transition-colors">
                                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                
                                <!-- Indicateur d'images -->
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                                    @foreach($listing->images as $index => $image)
                                        <button onclick="showImage({{ $index }})" class="w-2 h-2 rounded-full bg-white/80 hover:bg-white transition-colors {{ $index === 0 ? 'bg-white' : '' }}" data-image-index="{{ $index }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <div class="p-8">
                        <!-- Badge Non disponible -->
                        @if(!$listing->is_available)
                            <div class="mb-6 bg-red-500 text-white text-center py-3 px-6 rounded-lg font-bold text-lg shadow-lg">
                                ⚠️ Cette annonce n'est plus disponible
                            </div>
                        @endif
                        
                        <!-- En-tête avec titre et type -->
                        <div class="mb-6">
                            @php
                                $typeLabels = [
                                    'residential' => 'Résidentiel',
                                    'commercial' => 'Commercial',
                                    'land' => 'Terrain',
                                    'service' => 'Service',
                                ];
                                $typeColors = [
                                    'residential' => 'bg-blue-500',
                                    'commercial' => 'bg-purple-500',
                                    'land' => 'bg-amber-700',
                                    'service' => 'bg-orange-500',
                                ];
                            @endphp
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="inline-block {{ $typeColors[$listing->type] ?? 'bg-gray-500' }} text-white text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                                            {{ $typeLabels[$listing->type] ?? $listing->type }}
                                        </span>
                                        @if($listing->published_at)
                                            <span class="text-sm text-gray-500 whitespace-nowrap">
                                                Publié {{ $listing->published_at->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                    <h1 class="text-3xl font-bold text-gray-900 break-words">{{ $listing->title }}</h1>
                                </div>
                                @if($listing->price && $listing->type !== 'service')
                                    <div class="text-right flex-shrink-0">
                                        <div class="text-3xl font-bold text-orange-600 whitespace-nowrap">
                                            {{ number_format($listing->price, 0, ',', ' ') }} {{ $listing->currency ?? 'GNF' }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            @if($listing->address || $listing->city)
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-lg">{{ $listing->address }}{{ $listing->address && $listing->city ? ', ' : '' }}{{ $listing->city }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Description complète -->
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Description</h2>
                            <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $listing->description }}</p>
                        </div>
                        
                        <!-- Informations détaillées selon le type -->
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Caractéristiques</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @if($listing->type === 'residential')
                                    @if($listing->bedrooms)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $listing->bedrooms }}</div>
                                                <div class="text-sm text-gray-600">Chambre{{ $listing->bedrooms > 1 ? 's' : '' }}</div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($listing->bathrooms)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $listing->bathrooms }}</div>
                                                <div class="text-sm text-gray-600">Salle{{ $listing->bathrooms > 1 ? 's' : '' }} de bain</div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($listing->surface)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ number_format($listing->surface, 0, ',', ' ') }} m²</div>
                                                <div class="text-sm text-gray-600">Surface</div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                @elseif($listing->type === 'land')
                                    @if($listing->surface)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ number_format($listing->surface, 0, ',', ' ') }} m²</div>
                                                <div class="text-sm text-gray-600">Superficie</div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($listing->document_type)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $listing->document_type }}</div>
                                                <div class="text-sm text-gray-600">Type de document</div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($listing->price)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ number_format($listing->price, 0, ',', ' ') }} {{ $listing->currency }}</div>
                                                <div class="text-sm text-gray-600">Prix</div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                @elseif($listing->type === 'commercial')
                                    @if($listing->surface)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ number_format($listing->surface, 0, ',', ' ') }} m²</div>
                                                <div class="text-sm text-gray-600">Surface</div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($listing->price)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ number_format($listing->price, 0, ',', ' ') }} {{ $listing->currency }}</div>
                                                <div class="text-sm text-gray-600">Prix</div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                @elseif($listing->type === 'service')
                                    @if($listing->price)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <div class="font-semibold text-gray-900">À partir de {{ number_format($listing->price, 0, ',', ' ') }} {{ $listing->currency }}</div>
                                                <div class="text-sm text-gray-600">Tarif</div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            
                            <!-- Équipements/Amenities -->
                            @if($listing->amenities && count($listing->amenities) > 0)
                                <div class="mt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Équipements</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($listing->amenities as $amenity)
                                            <span class="inline-block bg-orange-100 text-orange-800 text-sm font-medium px-3 py-1 rounded-full">
                                                {{ $amenity }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Bouton Favoris (si utilisateur connecté) -->
                        @auth
                        <div class="mb-4">
                            <button
                                id="favorite-btn-{{ $listing->id }}"
                                onclick="toggleFavorite({{ $listing->id }})"
                                class="w-full text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"
                                style="background-color: #9ca3af;"
                            >
                                <svg id="favorite-icon-{{ $listing->id }}" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span id="favorite-text-{{ $listing->id }}">Ajouter aux favoris</span>
                            </button>
                        </div>
                        @endauth

                        <!-- Boutons d'action -->
                        <div class="mb-6 flex flex-col sm:flex-row gap-4">
                            <!-- Bouton Envoyer un message -->
                            <button
                                onclick="openMessageForm()"
                                id="messageBtn"
                                class="flex-1 bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Envoyer un message
                            </button>

                            <!-- Bouton Appeler -->
                            <a
                                href="tel:+224612345678"
                                class="flex-1 bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Appeler
                            </a>
                        </div>
                        
                        <!-- Section Réseaux Sociaux -->
                        @php
                            // Récupérer les liens sociaux depuis le modèle
                            // Le cast 'array' dans le modèle devrait automatiquement décoder le JSON
                            $socialLinksRaw = $listing->getRawOriginal('social_links') ?? $listing->social_links;
                            
                            // Gérer différents formats possibles
                            if (is_array($socialLinksRaw)) {
                                $socialLinks = $socialLinksRaw;
                            } elseif (is_string($socialLinksRaw) && !empty($socialLinksRaw)) {
                                $decoded = json_decode($socialLinksRaw, true);
                                $socialLinks = is_array($decoded) ? $decoded : [];
                            } elseif (is_null($socialLinksRaw)) {
                                $socialLinks = [];
                            } else {
                                $socialLinks = [];
                            }
                            
                            // Filtrer les valeurs vides, null ou non-string
                            $socialLinks = array_filter($socialLinks ?? [], function($value) {
                                return !empty($value) && is_string($value) && trim($value) !== '';
                            });
                        @endphp
                        @if(!empty($socialLinks) && count($socialLinks) > 0)
                            <div class="mb-6 pt-6 border-t border-gray-200">
                                <p class="text-sm text-gray-500 text-center mb-4">Voir l'annonce sur :</p>
                                <div class="flex justify-center items-center gap-4 flex-wrap">
                                    @if(!empty($socialLinks['facebook'] ?? ''))
                                        <a
                                            href="{{ $socialLinks['facebook'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg"
                                            title="Voir sur Facebook"
                                        >
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if(!empty($socialLinks['linkedin'] ?? ''))
                                        <a
                                            href="{{ $socialLinks['linkedin'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="w-12 h-12 rounded-full bg-blue-800 flex items-center justify-center hover:bg-blue-900 transition-colors shadow-md hover:shadow-lg"
                                            title="Voir sur LinkedIn"
                                        >
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if(!empty($socialLinks['twitter'] ?? ''))
                                        <a
                                            href="{{ $socialLinks['twitter'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="w-12 h-12 rounded-full bg-black flex items-center justify-center hover:bg-gray-800 transition-colors shadow-md hover:shadow-lg"
                                            title="Voir sur X (Twitter)"
                                        >
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if(!empty($socialLinks['instagram'] ?? ''))
                                        <a
                                            href="{{ $socialLinks['instagram'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="w-12 h-12 rounded-full bg-gradient-to-tr from-yellow-400 via-red-500 to-purple-500 flex items-center justify-center hover:opacity-90 transition-opacity shadow-md hover:shadow-lg"
                                            title="Voir sur Instagram"
                                        >
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                        </a>
                                    @endif

                                    @if(!empty($socialLinks['tiktok'] ?? ''))
                                        <a
                                            href="{{ $socialLinks['tiktok'] }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="w-12 h-12 rounded-full bg-black flex items-center justify-center hover:bg-gray-800 transition-colors shadow-md hover:shadow-lg"
                                            title="Voir sur TikTok"
                                        >
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Bouton Retour -->
                        <div class="pt-4 border-t border-gray-200">
                            <a
                                href="{{ route('home') }}"
                                class="block w-full bg-gray-500 text-white text-center py-3 rounded-lg font-semibold hover:bg-gray-600 hover:shadow-lg transition-all duration-300"
                            >
                                Retour
                            </a>
                        </div>
                    </div>
                </div>
                
                @if($listing->images && count($listing->images) > 1)
                    <script>
                        let currentImageIndex = 0;
                        const images = @json($listing->images);
                        const imageUrls = @json($imageUrls);
                        
                        function showImage(index) {
                            currentImageIndex = index;
                            document.getElementById('listing-main-image').src = imageUrls[index];
                            document.querySelectorAll('[data-image-index]').forEach((btn, i) => {
                                btn.classList.toggle('bg-white', i === index);
                                btn.classList.toggle('bg-white/80', i !== index);
                            });
                        }
                        
                        function nextImage() {
                            currentImageIndex = (currentImageIndex + 1) % images.length;
                            showImage(currentImageIndex);
                        }
                        
                        function previousImage() {
                            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
                            showImage(currentImageIndex);
                        }
                    </script>
                @endif
            </div>
        </main>
    </div>
    
    <script>
        function listingPageData() {
            return {
                showMessageForm: false,
                showSuccessModal: false,
                showErrorModal: false,
                errorMessage: '',
                messageSent: false,
                isSendingMessage: false,
                messageForm: {
                    name: '',
                    email: '',
                    phone: '',
                    message: ''
                },
                async openMessageForm() {
                    // Vérifier si l'utilisateur est connecté et récupérer ses données uniquement à ce moment
                    const isAuthenticated = @json(auth()->check());
                    
                    if (isAuthenticated && (!this.messageForm.name || !this.messageForm.email)) {
                        try {
                            // Récupérer les données de l'utilisateur connecté via une requête AJAX
                            const response = await fetch('/api/user-data', {
                                method: 'GET',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                },
                                credentials: 'same-origin'
                            });
                            
                            if (response.ok) {
                                const data = await response.json();
                                if (data.success && data.user) {
                                    this.messageForm.name = data.user.name || '';
                                    this.messageForm.email = data.user.email || '';
                                    this.messageForm.phone = data.user.phone || '';
                                }
                            }
                        } catch (error) {
                            console.error('Erreur lors de la récupération des données utilisateur:', error);
                            // En cas d'erreur, laisser les champs vides
                        }
                    }
                    
                    this.showMessageForm = true;
                    setTimeout(() => {
                        const scrollableContainer = this.$refs.messageFormScrollable;
                        if (scrollableContainer) {
                            scrollableContainer.scrollTop = 0;
                        }
                    }, 100);
                },
                closeMessageForm() {
                    this.showMessageForm = false;
                },
                closeSuccessModal() {
                    this.showSuccessModal = false;
                    document.body.style.overflow = '';
                },
                closeErrorModal() {
                    this.showErrorModal = false;
                    document.body.style.overflow = '';
                },
                async sendMessage() {
                    if (!this.messageForm.name || !this.messageForm.email || !this.messageForm.phone) {
                        this.errorMessage = 'Veuillez remplir tous les champs obligatoires.';
                        this.showMessageForm = false;
                        setTimeout(() => {
                            this.showErrorModal = true;
                            document.body.style.overflow = 'hidden';
                        }, 150);
                        return;
                    }
                    
                    this.isSendingMessage = true;
                    
                    const formData = {
                        listing_id: {{ $listing->id }},
                        name: this.messageForm.name,
                        email: this.messageForm.email,
                        phone: this.messageForm.phone,
                        message: this.messageForm.message || '',
                    };
                    
                    try {
                        const response = await fetch('{{ route("messages.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(formData)
                        });
                        
                        const data = await response.json();
                        if (response.ok && data.success) {
                            this.messageSent = true;
                            this.isSendingMessage = false;
                            this.showMessageForm = false;
                            this.messageForm.message = '';
                            // Afficher le modal de succès
                            setTimeout(() => {
                                this.showSuccessModal = true;
                                document.body.style.overflow = 'hidden';
                            }, 150);
                        } else {
                            const errors = data.errors || {};
                            let errorMessage = "Une erreur est survenue lors de l'envoi du message.";
                            if (Object.keys(errors).length > 0) {
                                errorMessage = Object.values(errors).flat().join('\n');
                            }
                            this.errorMessage = errorMessage;
                            this.isSendingMessage = false;
                            this.showMessageForm = false;
                            setTimeout(() => {
                                this.showErrorModal = true;
                                document.body.style.overflow = 'hidden';
                            }, 150);
                        }
                    } catch (error) {
                        console.error("Erreur lors de l'envoi du message:", error);
                        this.errorMessage = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
                        this.isSendingMessage = false;
                        this.showMessageForm = false;
                        setTimeout(() => {
                            this.showErrorModal = true;
                            document.body.style.overflow = 'hidden';
                        }, 150);
                    }
                }
            };
        }
        
        function openMessageForm() {
            if (window.Alpine && window.Alpine.store) {
                // Si Alpine.js est chargé, utiliser la méthode Alpine
                const component = Alpine.$data(document.querySelector('[x-data]'));
                if (component && component.openMessageForm) {
                    component.openMessageForm();
                }
            }
        }
        
        // Vérifier si l'annonce est en favoris au chargement de la page
        @auth
        document.addEventListener('DOMContentLoaded', async function() {
            await checkFavorite({{ $listing->id }});
        });
        
        async function checkFavorite(listingId) {
            try {
                const response = await fetch(`/listings/${listingId}/favorite/check`);
                const data = await response.json();
                updateFavoriteButton(listingId, data.is_favorite || false);
            } catch (error) {
                console.error('Erreur lors de la vérification des favoris:', error);
            }
        }
        
        async function toggleFavorite(listingId) {
            const btn = document.getElementById(`favorite-btn-${listingId}`);
            const isFavorite = btn.style.backgroundColor === 'rgb(239, 68, 68)' || btn.style.backgroundColor === '#ef4444';
            
            try {
                const url = `/listings/${listingId}/favorite`;
                const method = isFavorite ? 'DELETE' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    updateFavoriteButton(listingId, data.is_favorite);
                } else {
                    if (data.message && data.message.includes('connecté')) {
                        window.location.href = '/login';
                    } else {
                        alert(data.message || 'Une erreur est survenue');
                    }
                }
            } catch (error) {
                console.error('Erreur lors de l\'ajout/retrait des favoris:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            }
        }
        
        function updateFavoriteButton(listingId, isFavorite) {
            const btn = document.getElementById(`favorite-btn-${listingId}`);
            const icon = document.getElementById(`favorite-icon-${listingId}`);
            const text = document.getElementById(`favorite-text-${listingId}`);
            
            if (btn && icon && text) {
                if (isFavorite) {
                    btn.style.backgroundColor = '#ef4444';
                    icon.innerHTML = '<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
                    icon.setAttribute('fill', 'currentColor');
                    text.textContent = 'Retirer des favoris';
                } else {
                    btn.style.backgroundColor = '#9ca3af';
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
                    icon.removeAttribute('fill');
                    text.textContent = 'Ajouter aux favoris';
                }
            }
        }
        @endauth
    </script>
    
    <!-- Modal Formulaire d'envoi de message -->
    <div
        x-show="showMessageForm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.away="closeMessageForm()"
        @keydown.escape.window="closeMessageForm()"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div
            x-show="showMessageForm"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            @click.stop
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto relative"
            style="display: none;"
        >
            <div class="sticky top-0 bg-white z-10 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Envoyer un message</h2>
                <button @click="closeMessageForm()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6" x-ref="messageFormScrollable">
                <form @submit.prevent="sendMessage()" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                        <input
                            type="text"
                            id="name"
                            x-model="messageForm.name"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Votre nom"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input
                            type="email"
                            id="email"
                            x-model="messageForm.email"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="votre@email.com"
                        >
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input
                            type="tel"
                            id="phone"
                            x-model="messageForm.phone"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="+224 XXX XXX XXX"
                        >
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea
                            id="message"
                            x-model="messageForm.message"
                            required
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            placeholder="Votre message..."
                            style="margin-bottom: 0; padding-bottom: 0;"
                        ></textarea>
                    </div>

                    <div class="flex gap-3 pt-2" style="margin-top: 1cm;">
                        <button
                            type="button"
                            @click="closeMessageForm()"
                            class="flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            :disabled="isSendingMessage"
                            :class="isSendingMessage ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-600'"
                            class="flex-1 bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
                        >
                            <span x-show="!isSendingMessage">Envoyer</span>
                            <span x-show="isSendingMessage" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Envoi en cours...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal d'Erreur -->
        <div
            x-show="showErrorModal"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.away="closeErrorModal()"
            @keydown.escape.window="closeErrorModal()"
            class="fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4"
        >
            <div
                x-show="showErrorModal"
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 transform scale-95 translate-y-4"
                @click.stop
                class="bg-white rounded-2xl shadow-2xl w-full max-w-xs lg:max-w-[5cm] relative overflow-hidden"
            >
                <!-- Icône d'erreur animée -->
                <div class="bg-gradient-to-br from-red-400 to-red-600 p-6 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-3 animate-bounce">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>

                <!-- Contenu -->
                <div class="p-5 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Erreur d'envoi</h3>
                    <p class="text-sm text-gray-600 mb-5" x-text="errorMessage">
                    </p>
                    <button
                        @click="closeErrorModal()"
                        class="w-full px-5 py-2.5 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 hover:shadow-lg transition-all duration-300 text-sm"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal de Succès - Design Professionnel Carré -->
        <div
            x-show="showSuccessModal"
            x-cloak
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 backdrop-blur-0"
            x-transition:enter-end="opacity-100 backdrop-blur-sm"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 backdrop-blur-sm"
            x-transition:leave-end="opacity-0 backdrop-blur-0"
            @click.away="closeSuccessModal()"
            @keydown.escape.window="closeSuccessModal()"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4"
            style="display: none;"
        >
            <div
                x-show="showSuccessModal"
                x-cloak
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 transform scale-75 rotate-12 translate-y-8"
                x-transition:enter-end="opacity-100 transform scale-100 rotate-0 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100 rotate-0 translate-y-0"
                x-transition:leave-end="opacity-0 transform scale-75 -rotate-12 translate-y-8"
                @click.stop
                class="bg-white rounded-3xl shadow-2xl relative overflow-hidden w-full max-w-sm sm:max-w-md md:max-w-lg sm:aspect-square flex flex-col min-h-[400px] sm:min-h-0"
                style="display: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);"
            >
                <!-- Header avec gradient animé -->
                <div class="relative bg-gradient-to-br from-[#86c14f] via-[#87c04f] to-[#6ba13f] p-6 sm:p-8 flex-shrink-0">
                    <!-- Effet de brillance animé -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-[shimmer_3s_ease-in-out_infinite]"></div>
                    
                    <!-- Icône de succès avec animation -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-full mb-4 shadow-xl animate-[scaleBounce_0.6s_ease-out]">
                            <svg class="w-12 h-12 sm:w-14 sm:h-14 text-[#86c14f] animate-[checkmark_0.6s_ease-out_0.2s_both]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-bold text-white drop-shadow-lg">Message envoyé !</h3>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div class="flex-1 flex flex-col justify-between p-6 sm:p-8">
                    <!-- Message de confirmation -->
                    <div class="text-center mb-6">
                        <p class="text-base sm:text-lg text-gray-700 leading-relaxed">
                            Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.
                        </p>
                    </div>
                    
                    <!-- Message d'invitation à se connecter (uniquement si non connecté) -->
                    @guest
                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 border-2 border-orange-300 rounded-2xl p-5 sm:p-6 mb-6 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-lg">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center shadow-md animate-pulse">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm sm:text-base font-semibold text-orange-900 mb-1">💡 Astuce</p>
                                <p class="text-xs sm:text-sm text-orange-800 leading-relaxed">
                                    Connectez-vous pour suivre vos annonces et messages dans votre dashboard.
                                </p>
                            </div>
                        </div>
                        <a
                            href="{{ route('login') }}"
                            class="block w-full px-5 py-3 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-xl font-bold text-sm sm:text-base hover:from-[#f97316] hover:to-[#ea580c] hover:shadow-xl transform transition-all duration-300 hover:scale-105 active:scale-95 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Se connecter
                        </a>
                    </div>
                    @endguest
                    
                    <!-- Bouton Fermer -->
                    <button
                        @click="closeSuccessModal()"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-bold text-sm sm:text-base hover:from-gray-600 hover:to-gray-700 hover:shadow-xl transform transition-all duration-300 hover:scale-105 active:scale-95 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Fermer
                    </button>
                </div>

                <!-- Effet de brillance décoratif en bas -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-[#86c14f] to-transparent opacity-50"></div>
            </div>
        </div>

        <style>
            @keyframes shimmer {
                0% { transform: translateX(-100%) skewX(-15deg); }
                100% { transform: translateX(200%) skewX(-15deg); }
            }
            
            @keyframes scaleBounce {
                0% { transform: scale(0); opacity: 0; }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); opacity: 1; }
            }
            
            @keyframes checkmark {
                0% { 
                    stroke-dasharray: 0 50;
                    stroke-dashoffset: 0;
                    opacity: 0;
                }
                50% {
                    opacity: 1;
                }
                100% { 
                    stroke-dasharray: 50 0;
                    stroke-dashoffset: 0;
                    opacity: 1;
                }
            }
        </style>
    </div>
</body>
</html>

