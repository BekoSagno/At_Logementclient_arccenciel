@props(['listing'])

@php
    // Si service_status existe, l'utiliser pour le badge
    if ($listing->service_status) {
        $statusLabels = [
            'recherche' => 'Recherche',
            'propose' => 'Propose',
            'realise' => 'Réalisé',
        ];
        $statusColors = [
            'recherche' => 'bg-blue-500',
            'propose' => 'bg-green-500',
            'realise' => 'bg-purple-500',
        ];
        $typeColor = $statusColors[$listing->service_status] ?? 'bg-gray-500';
        $typeLabel = $statusLabels[$listing->service_status] ?? ucfirst($listing->service_status);
    } else {
        // Sinon, utiliser le type comme avant
        $typeColors = [
            'residential' => ['bg-blue-500', 'bg-green-500'],
            'commercial' => ['bg-purple-500'],
            'land' => ['bg-amber-700'],
            'service' => ['bg-orange-500'],
        ];
        
        $typeLabels = [
            'residential' => 'Résidentiel',
            'commercial' => 'Commercial',
            'land' => 'Terrain',
            'service' => 'Service',
        ];
        
        $typeColor = $typeColors[$listing->type][0] ?? 'bg-gray-500';
        $typeLabel = $typeLabels[$listing->type] ?? $listing->type;
    }
@endphp

<div class="listing-card bg-white rounded-2xl shadow-lg overflow-hidden group h-full flex flex-col {{ !$listing->is_available ? 'opacity-75' : '' }}">
    <!-- Badge Non disponible -->
    @if(!$listing->is_available)
        <div class="bg-red-500 text-white text-center py-2 px-4 font-bold text-sm">
            ⚠️ Non disponible
        </div>
    @endif
    
    <!-- Image Container -->
    <div class="relative h-48 overflow-hidden {{ !$listing->is_available ? 'grayscale' : '' }}">
        @if($listing->thumbnail)
            @php
                $imageUrl = $listing->thumbnail;
                if (!str_starts_with($imageUrl, 'http') && !str_starts_with($imageUrl, '/')) {
                    // Les images sont stockées dans storage/app/public/listings/
                    $filePath = str_starts_with($imageUrl, 'listings/') ? $imageUrl : 'listings/' . $imageUrl;
                    
                    // Essayer d'abord le disque public (où Filament stocke les fichiers)
                    if (Storage::disk('public')->exists($filePath)) {
                        // Utiliser Storage::url() qui génère l'URL correcte avec APP_URL
                        $imageUrl = Storage::disk('public')->url($filePath);
                    } elseif (Storage::disk('public')->exists($imageUrl)) {
                        // Si le chemin est déjà correct
                        $imageUrl = Storage::disk('public')->url($imageUrl);
                    } elseif (Storage::disk('local')->exists($filePath)) {
                        // Fallback: utiliser la route pour servir les fichiers du disque local
                        $imageUrl = route('listing.image', ['path' => str_replace('listings/', '', $imageUrl)]);
                    } else {
                        // Utiliser la route comme dernier recours
                        $imageUrl = route('listing.image', ['path' => str_replace('listings/', '', $imageUrl)]);
                    }
                }
            @endphp
            <img 
                src="{{ $imageUrl }}" 
                alt="{{ $listing->title }}"
                class="image-hover-zoom w-full h-full object-cover"
            >
        @else
            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif
        
        <!-- Badge Type -->
        <div class="absolute top-3 left-3">
            <span class="inline-block {{ $typeColor }} text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                {{ $typeLabel }}
            </span>
        </div>
        
        <!-- Badge Prix (sauf pour Service) -->
        @if($listing->type !== 'service' && $listing->price)
            <div class="absolute top-3 right-3">
                <span class="inline-block bg-gradient-to-r from-at-orange to-at-orange-600 text-white text-sm font-bold px-4 py-1.5 rounded-full shadow-md">
                    {{ number_format($listing->price, 0, ',', ' ') }} {{ $listing->currency }}
                </span>
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="p-6 flex-1 flex flex-col">
        <!-- Titre -->
        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-at-orange transition-colors duration-300 line-clamp-2">
            {{ $listing->title }}
        </h3>

        <!-- Description -->
        <p class="text-gray-600 mb-4 text-sm line-clamp-2 flex-1">
            {{ Str::limit($listing->description, 100) }}
        </p>

        <!-- Informations conditionnelles selon le type -->
        <div class="mb-6 space-y-3">
            @if($listing->type === 'residential')
                <!-- Residential: Prix, Adresse, Lits, Douches, Surface -->
                @if($listing->address || $listing->city)
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm">{{ $listing->address }}{{ $listing->address && $listing->city ? ', ' : '' }}{{ $listing->city }}</span>
                    </div>
                @endif
                
                <div class="flex items-center gap-4 text-gray-500">
                    @if($listing->bedrooms)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-5 h-5 text-at-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $listing->bedrooms }} chambre{{ $listing->bedrooms > 1 ? 's' : '' }}</span>
                        </div>
                    @endif
                    
                    @if($listing->bathrooms)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-5 h-5 text-at-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $listing->bathrooms }} salle{{ $listing->bathrooms > 1 ? 's' : '' }} de bain</span>
                        </div>
                    @endif
                    
                    @if($listing->surface)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-5 h-5 text-at-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ number_format($listing->surface, 0, ',', ' ') }} m²</span>
                        </div>
                    @endif
                </div>
                
            @elseif($listing->type === 'land')
                <!-- Land: Prix, Adresse, Surface, Document Type -->
                @if($listing->address || $listing->city)
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm">{{ $listing->address }}{{ $listing->address && $listing->city ? ', ' : '' }}{{ $listing->city }}</span>
                    </div>
                @endif
                
                <div class="flex items-center gap-4 text-gray-500 flex-wrap">
                    @if($listing->surface)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-5 h-5 text-at-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ number_format($listing->surface, 0, ',', ' ') }} m²</span>
                        </div>
                    @endif
                    
                    @if($listing->document_type)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-5 h-5 text-at-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $listing->document_type }}</span>
                        </div>
                    @endif
                </div>
                
            @elseif($listing->type === 'service')
                <!-- Service: Titre, Description, Prix "À partir de..." -->
                @if($listing->price)
                    <div class="flex items-center gap-2 text-at-orange font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm">À partir de {{ number_format($listing->price, 0, ',', ' ') }} {{ $listing->currency }}</span>
                    </div>
                @endif
            @elseif($listing->type === 'commercial')
                <!-- Commercial: Prix, Adresse, Surface -->
                @if($listing->address || $listing->city)
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm">{{ $listing->address }}{{ $listing->address && $listing->city ? ', ' : '' }}{{ $listing->city }}</span>
                    </div>
                @endif
                
                @if($listing->surface)
                    <div class="flex items-center gap-1.5 text-gray-500">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ number_format($listing->surface, 0, ',', ' ') }} m²</span>
                    </div>
                @endif
            @endif
        </div>

        <!-- Bouton d'action -->
        <div class="mt-auto">
            @if($listing->type === 'service')
                <a 
                    href="#contact" 
                    class="btn-primary block w-full bg-at-orange text-white text-center py-3 rounded-lg font-semibold hover:bg-at-orange-600 hover:shadow-at-orange active:scale-95 transition-all duration-300"
                >
                    Demander un devis
                </a>
            @else
                @php
                    // Préparer les URLs d'images pour le modal
                    $imageUrls = [];
                    if ($listing->images && count($listing->images) > 0) {
                        foreach($listing->images as $img) {
                            if (!str_starts_with($img, 'http') && !str_starts_with($img, '/')) {
                                // Les images sont stockées dans storage/app/public/listings/
                                $filePath = str_starts_with($img, 'listings/') ? $img : 'listings/' . $img;
                                
                                // Essayer d'abord le disque public (où Filament stocke les fichiers)
                                if (Storage::disk('public')->exists($filePath)) {
                                    $imageUrls[] = Storage::disk('public')->url($filePath);
                                } elseif (Storage::disk('public')->exists($img)) {
                                    $imageUrls[] = Storage::disk('public')->url($img);
                                } elseif (Storage::disk('local')->exists($filePath)) {
                                    // Fallback: utiliser la route pour servir les fichiers du disque local
                                    $imageUrls[] = route('listing.image', ['path' => str_replace('listings/', '', $img)]);
                                } else {
                                    // Utiliser la route comme dernier recours
                                    $imageUrls[] = route('listing.image', ['path' => str_replace('listings/', '', $img)]);
                                }
                            } else {
                                $imageUrls[] = $img;
                            }
                        }
                    }
                @endphp
                @php
                    // Préparer les liens sociaux pour le modal
                    $socialLinks = $listing->social_links ?? [];
                    if (is_string($socialLinks)) {
                        $socialLinks = json_decode($socialLinks, true) ?? [];
                    }
                    if (!is_array($socialLinks)) {
                        $socialLinks = [];
                    }
                    // Filtrer les valeurs vides/null
                    $socialLinks = array_filter($socialLinks, function($value) {
                        return !empty($value) && is_string($value) && trim($value) !== '';
                    });
                @endphp
                <button 
                    onclick="openListingModal(@js([
                        'id' => $listing->id,
                        'title' => $listing->title,
                        'description' => $listing->description,
                        'price' => $listing->price,
                        'currency' => $listing->currency ?? 'GNF',
                        'type' => $listing->type,
                        'service_status' => $listing->service_status,
                        'address' => $listing->address,
                        'city' => $listing->city,
                        'bedrooms' => $listing->bedrooms,
                        'bathrooms' => $listing->bathrooms,
                        'surface' => $listing->surface,
                        'images' => $listing->images ?? [],
                        'imageUrls' => $imageUrls,
                        'social_links' => $socialLinks,
                        'custom_fields' => $listing->custom_fields ?? [],
                    ]))" 
                    class="btn-primary block w-full bg-at-orange text-white text-center py-3 rounded-lg font-semibold hover:bg-at-orange-600 hover:shadow-at-orange active:scale-95 transition-all duration-300"
                >
                    Voir les détails
                </button>
            @endif
        </div>
    </div>
</div>

