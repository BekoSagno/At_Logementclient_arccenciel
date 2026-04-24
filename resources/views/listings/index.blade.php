<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Recherche d'annonces - AT Logement</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .double-underline {
            position: relative;
            display: inline-block;
        }
        .double-underline::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #f97316, #ea580c);
            transform: skew(-8deg);
        }
    </style>
    <script>
        // Scroller automatiquement vers la section des résultats au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si la page a été chargée avec des paramètres de recherche
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('query') || urlParams.has('location') || urlParams.has('type') || 
                urlParams.has('transaction') || urlParams.has('bedrooms') || 
                urlParams.has('budget_min') || urlParams.has('budget_max')) {
                
                // Attendre un peu pour que le contenu soit chargé
                setTimeout(function() {
                    const resultatsSection = document.getElementById('resultats');
                    if (resultatsSection) {
                        resultatsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }, 100);
            }
        });
    </script>
</head>
<body class="font-sans antialiased bg-white">
    <!-- HEADER -->
    <x-header />

    <!-- MAIN CONTENT -->
    <main class="pt-14 lg:pt-20">
        <!-- Section Filtres (toujours visible) -->
        <section class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrer les résultats</h3>
                <form action="{{ route('listings.search') }}" method="GET" class="space-y-4">
                    <!-- Ligne 1: Recherche textuelle -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input
                                type="text"
                                name="query"
                                value="{{ request('query') }}"
                                placeholder="Rechercher un bien, un quartier, une ville..."
                                class="w-full px-4 py-2 pl-12 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300"
                            >
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Ligne 2: Filtres principaux -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Localisation -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Localisation</label>
                            <select name="location" class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300">
                                <option value="">Toutes les villes</option>
                                <option value="conakry" {{ request('location') == 'conakry' ? 'selected' : '' }}>Conakry</option>
                                <option value="kindia" {{ request('location') == 'kindia' ? 'selected' : '' }}>Kindia</option>
                                <option value="kankan" {{ request('location') == 'kankan' ? 'selected' : '' }}>Kankan</option>
                                <option value="nzerekore" {{ request('location') == 'nzerekore' ? 'selected' : '' }}>Nzérékoré</option>
                                <option value="labé" {{ request('location') == 'labé' ? 'selected' : '' }}>Labé</option>
                            </select>
                        </div>

                        <!-- Type de bien -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Type de bien</label>
                            <select name="type" class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300">
                                <option value="">Tous les types</option>
                                <option value="residential" {{ request('type') == 'residential' ? 'selected' : '' }}>Résidentiel</option>
                                <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="land" {{ request('type') == 'land' ? 'selected' : '' }}>Terrain</option>
                                <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Service</option>
                            </select>
                        </div>

                        <!-- Transaction -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction</label>
                            <select name="transaction" class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300">
                                <option value="">Vente/Location</option>
                                <option value="vente" {{ request('transaction') == 'vente' ? 'selected' : '' }}>Vente</option>
                                <option value="location" {{ request('transaction') == 'location' ? 'selected' : '' }}>Location</option>
                            </select>
                        </div>

                        <!-- Nombre de chambres -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Chambres</label>
                            <select name="bedrooms" class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300">
                                <option value="">Toutes</option>
                                <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1+</option>
                                <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2+</option>
                                <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3+</option>
                                <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4+</option>
                                <option value="5" {{ request('bedrooms') == '5' ? 'selected' : '' }}>5+</option>
                            </select>
                        </div>
                    </div>

                    <!-- Ligne 3: Budget -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Budget Min -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Budget Min (GNF)</label>
                            <input
                                type="number"
                                name="budget_min"
                                value="{{ request('budget_min') }}"
                                placeholder="Ex: 5000000"
                                class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300"
                            >
                        </div>

                        <!-- Budget Max -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Budget Max (GNF)</label>
                            <input
                                type="number"
                                name="budget_max"
                                value="{{ request('budget_max') }}"
                                placeholder="Ex: 20000000"
                                class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300"
                            >
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a
                            href="{{ route('home') }}"
                            class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-300"
                        >
                            Retour
                        </a>
                        <button
                            type="submit"
                            class="px-6 py-2 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 hover:shadow-lg transition-all duration-300"
                        >
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Section Résultats -->
        <section id="resultats" class="py-20 bg-gradient-to-b from-gray-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                        <span class="double-underline">Résultats de recherche</span>
                    </h2>
                    @if($listings->total() > 0)
                        <p class="text-gray-600 text-lg">
                            {{ $listings->total() }} annonce{{ $listings->total() > 1 ? 's' : '' }} trouvée{{ $listings->total() > 1 ? 's' : '' }}
                        </p>
                    @endif
                </div>

                <!-- Message si aucun résultat -->
                @forelse($listings as $listing)
                    @if($loop->first)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @endif
                    <x-listing-card :listing="$listing" />
                    @if($loop->last)
                        </div>
                    @endif
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg mb-2">Aucune annonce ne correspond à vos critères de recherche.</p>
                        <p class="text-gray-400 text-sm mb-6">Essayez de modifier vos filtres ou votre recherche.</p>
                        <a
                            href="{{ route('home') }}"
                            class="inline-block px-6 py-2 bg-orange-500 text-white rounded-lg font-semibold hover:bg-orange-600 hover:shadow-lg transition-all duration-300"
                        >
                            Retour à l'accueil
                        </a>
                    </div>
                @endforelse

                <!-- Pagination -->
                @if($listings->hasPages())
                    <div class="mt-12">
                        {{ $listings->links() }}
                    </div>
                @endif
            </div>
        </section>
    </main>
</body>
</html>

