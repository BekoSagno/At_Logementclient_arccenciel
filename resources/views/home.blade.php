<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AT Immobilier - Votre partenaire immobilier en Guinée</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Header -->
    <x-header />

    <!-- Main Content -->
    <main class="pt-14 md:pt-16">
        <!-- Section Hero -->
        <section class="relative h-[400px] sm:h-[500px] md:h-[550px] lg:h-[600px] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=2070');">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 h-full flex flex-col justify-center items-center">
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white text-center mb-6 md:mb-8 max-w-3xl px-4">
                    Trouvez votre futur chez-vous en Guinée
                </h1>

                <!-- Barre de Recherche Card -->
                <div class="w-full max-w-4xl bg-white rounded-xl md:rounded-2xl shadow-2xl p-3 sm:p-4 md:p-5 mx-3 sm:mx-4">
                    <form class="flex flex-col md:flex-row gap-3 md:gap-4">
                        <!-- Localisation -->
                        <div class="flex-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Localisation</label>
                            <input
                                type="text"
                                placeholder="Où cherchez-vous ?"
                                class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg md:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                            >
                        </div>

                        <!-- Type de bien -->
                        <div class="flex-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Type de bien</label>
                            <select class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg md:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                <option value="">Tous les types</option>
                                <option value="maison">Maison</option>
                                <option value="appartement">Appartement</option>
                                <option value="villa">Villa</option>
                                <option value="terrain">Terrain</option>
                            </select>
                        </div>

                        <!-- Budget -->
                        <div class="flex-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Budget</label>
                            <select class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg md:rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                                <option value="">Tous les budgets</option>
                                <option value="0-5000000">0 - 5M GNF</option>
                                <option value="5000000-10000000">5M - 10M GNF</option>
                                <option value="10000000-20000000">10M - 20M GNF</option>
                                <option value="20000000+">20M+ GNF</option>
                            </select>
                        </div>

                        <!-- Bouton Rechercher -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-6 md:px-8 py-2.5 sm:py-3 text-sm md:text-base rounded-lg md:rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                                Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Section Dernières Opportunités -->
        <section class="py-8 sm:py-12 md:py-16 bg-white">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6 md:mb-8 text-center">Dernières Opportunités</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                    <!-- Card 1 -->
                    <div class="bg-white rounded-xl md:rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="h-40 sm:h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075');"></div>
                        <div class="p-4 sm:p-5 md:p-6">
                            <div class="text-xl sm:text-2xl font-bold text-blue-600 mb-2">45 000 000 GNF</div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Villa moderne à Conakry</h3>
                            <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">Quartier Camayenne, Conakry</p>
                            <div class="flex items-center gap-3 sm:gap-4 text-sm sm:text-base text-gray-500">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <span>4 chambres</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                    <span>3 salles de bain</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-xl md:rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="h-40 sm:h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=2053');"></div>
                        <div class="p-4 sm:p-5 md:p-6">
                            <div class="text-xl sm:text-2xl font-bold text-blue-600 mb-2">25 000 000 GNF</div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Appartement spacieux</h3>
                            <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">Quartier Almamya, Conakry</p>
                            <div class="flex items-center gap-3 sm:gap-4 text-sm sm:text-base text-gray-500">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <span>3 chambres</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                    <span>2 salles de bain</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-xl md:rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="h-40 sm:h-48 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?q=80&w=2070');"></div>
                        <div class="p-4 sm:p-5 md:p-6">
                            <div class="text-xl sm:text-2xl font-bold text-blue-600 mb-2">60 000 000 GNF</div>
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Villa de luxe avec piscine</h3>
                            <p class="text-sm sm:text-base text-gray-600 mb-3 sm:mb-4">Quartier Dixinn, Conakry</p>
                            <div class="flex items-center gap-3 sm:gap-4 text-sm sm:text-base text-gray-500">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    <span>5 chambres</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                    <span>4 salles de bain</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Nos Services -->
        <section class="py-8 sm:py-12 md:py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6 md:mb-8 text-center">Nos Services</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
                    <!-- Service 1 -->
                    <div class="bg-white rounded-xl md:rounded-2xl p-5 sm:p-6 md:p-8 shadow-lg text-center hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Dépannage SOS 24/7</h3>
                        <p class="text-sm sm:text-base text-gray-600">Assistance urgente disponible 24h/24 et 7j/7 pour tous vos besoins immobiliers.</p>
                    </div>

                    <!-- Service 2 -->
                    <div class="bg-white rounded-xl md:rounded-2xl p-5 sm:p-6 md:p-8 shadow-lg text-center hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Nettoyage</h3>
                        <p class="text-sm sm:text-base text-gray-600">Service de nettoyage professionnel pour préparer votre bien à la vente ou location.</p>
                    </div>

                    <!-- Service 3 -->
                    <div class="bg-white rounded-xl md:rounded-2xl p-5 sm:p-6 md:p-8 shadow-lg text-center hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Déménagement</h3>
                        <p class="text-sm sm:text-base text-gray-600">Solutions complètes de déménagement avec équipe professionnelle et matériel adapté.</p>
                    </div>

                    <!-- Service 4 -->
                    <div class="bg-white rounded-xl md:rounded-2xl p-5 sm:p-6 md:p-8 shadow-lg text-center hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Gestion Locative</h3>
                        <p class="text-sm sm:text-base text-gray-600">Gestion complète de vos biens locatifs : location, entretien et suivi des locataires.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Confiance -->
        <section class="py-8 sm:py-12 md:py-16 bg-white">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 md:gap-12 items-center">
                    <div>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4 sm:mb-6">Pourquoi choisir AT Immobilier ?</h2>
                        <p class="text-base sm:text-lg text-gray-600 mb-4 sm:mb-6">
                            Avec plus de 10 ans d'expérience sur le marché immobilier guinéen, AT Immobilier est votre partenaire de confiance pour tous vos projets immobiliers.
                        </p>
                        <ul class="space-y-3 sm:space-y-4">
                            <li class="flex items-start gap-2 sm:gap-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 flex-shrink-0 mt-0.5 sm:mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm sm:text-base text-gray-700">Expertise locale approfondie du marché guinéen</span>
                            </li>
                            <li class="flex items-start gap-2 sm:gap-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 flex-shrink-0 mt-0.5 sm:mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm sm:text-base text-gray-700">Accompagnement personnalisé à chaque étape</span>
                            </li>
                            <li class="flex items-start gap-2 sm:gap-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 flex-shrink-0 mt-0.5 sm:mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm sm:text-base text-gray-700">Portfolio varié de biens sélectionnés avec soin</span>
                            </li>
                            <li class="flex items-start gap-2 sm:gap-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 flex-shrink-0 mt-0.5 sm:mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm sm:text-base text-gray-700">Transparence totale dans toutes les transactions</span>
                            </li>
                        </ul>
                    </div>
                    <div class="rounded-xl md:rounded-2xl overflow-hidden shadow-xl">
                        <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=2073" alt="Équipe AT Immobilier" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 sm:py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 md:gap-12">
                <!-- Logo & Description -->
                <div class="sm:col-span-2">
                    <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">AT Immobilier</h3>
                    <p class="text-sm sm:text-base text-gray-400 mb-4">
                        Votre partenaire de confiance pour tous vos projets immobiliers en Guinée.
                    </p>
                </div>

                <!-- Liens rapides -->
                <div>
                    <h4 class="text-sm sm:text-base font-semibold mb-3 sm:mb-4">Liens rapides</h4>
                    <ul class="space-y-2 text-sm sm:text-base text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Accueil</a></li>
                        <li><a href="#acheter" class="hover:text-white transition-colors">Acheter</a></li>
                        <li><a href="#louer" class="hover:text-white transition-colors">Louer</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-sm sm:text-base font-semibold mb-3 sm:mb-4">Services</h4>
                    <ul class="space-y-2 text-sm sm:text-base text-gray-400">
                        <li><a href="#service-1" class="hover:text-white transition-colors">Dépannage SOS 24/7</a></li>
                        <li><a href="#service-2" class="hover:text-white transition-colors">Nettoyage</a></li>
                        <li><a href="#service-3" class="hover:text-white transition-colors">Déménagement</a></li>
                        <li><a href="#service-4" class="hover:text-white transition-colors">Gestion Locative</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-6 sm:mt-8 md:mt-12 pt-6 sm:pt-8 text-center text-sm sm:text-base text-gray-400">
                <p>&copy; {{ date('Y') }} AT Immobilier. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
