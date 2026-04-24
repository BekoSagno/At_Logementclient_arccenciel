<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Prévisualisation : {{ $listing->title }} - AT Logement</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Bandeau de prévisualisation -->
        <div class="bg-amber-500 text-white py-3 px-4 shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span class="font-semibold">Mode Prévisualisation</span>
                    <span class="text-sm opacity-90">- Cette annonce n'est pas encore publiée publiquement</span>
                </div>
                <a href="{{ route('filament.admin.resources.listings.edit', $listing) }}" class="bg-white text-amber-600 px-4 py-2 rounded-lg font-semibold hover:bg-amber-50 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à l'édition
                </a>
            </div>
        </div>

        <x-header />
        
        <main class="pt-20 pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ route('filament.admin.resources.listings.index') }}" class="inline-flex items-center text-orange-600 hover:text-orange-700 mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour aux annonces
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
                                        @else
                                            <span class="text-sm text-amber-600 font-semibold whitespace-nowrap">
                                                Brouillon
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
                        
                        <!-- Section Réseaux Sociaux -->
                        @php
                            $socialLinksRaw = $listing->getRawOriginal('social_links') ?? $listing->social_links;
                            
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
                                href="{{ route('filament.admin.resources.listings.edit', $listing) }}"
                                class="block w-full bg-orange-500 text-white text-center py-3 rounded-lg font-semibold hover:bg-orange-600 hover:shadow-lg transition-all duration-300"
                            >
                                Modifier l'annonce
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
</body>
</html>
