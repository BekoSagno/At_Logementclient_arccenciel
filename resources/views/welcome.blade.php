<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AT Logement - Votre partenaire immobilier en Guinée</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles for Animations -->
        <style>
        [x-cloak] { display: none !important; }
        
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-scale {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(30px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes slide-in-left {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slide-in-right {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes zoom-banner {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.1);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s ease-out;
        }

        .animate-fade-in-up-delay {
            animation: fade-in-up 1s ease-out 0.3s backwards;
        }

        .animate-fade-in-up-delay-2 {
            animation: fade-in-scale 1s ease-out 0.6s backwards;
        }

        .animate-slide-in-left {
            animation: slide-in-left 1s ease-out;
        }

        .animate-slide-in-right {
            animation: slide-in-right 1s ease-out 0.3s backwards;
        }

        .hover\:shadow-3xl:hover {
            box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
        }

        .banner-bg-animated {
            animation: zoom-banner 20s ease-in-out infinite alternate;
        }

        /* Transition pour la section Services */
        #services {
            scroll-margin-top: 80px;
        }

        @keyframes fade-in-section {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .services-section {
            animation: fade-in-section 0.8s ease-out;
        }

        /* Animation pour les cartes de services */
        @keyframes fade-in-up-service {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .service-card {
            opacity: 0;
            animation: fade-in-up-service 0.6s ease-out forwards;
        }

        .service-card-1 {
            animation-delay: 0.1s;
        }

        .service-card-2 {
            animation-delay: 0.2s;
        }

        .service-card-3 {
            animation-delay: 0.3s;
        }

        .service-card-4 {
            animation-delay: 0.4s;
        }

        /* Style pour les doubles lignes de soulignement courbées */
        .double-underline {
            position: relative;
            display: inline-block;
        }

        .double-underline::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0.5cm;
            right: 0.5cm;
            height: 4px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='4'%3E%3Cpath d='M 0 2 Q 50 0.5 100 2' stroke='%23f97316' stroke-width='2.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-size: 100% 100%;
            background-repeat: no-repeat;
            transform: skew(-8deg);
        }

        .double-underline::before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0.5cm;
            right: 0.5cm;
            height: 4px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='4'%3E%3Cpath d='M 0 2 Q 50 0.5 100 2' stroke='%2386c14f' stroke-width='2.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-size: 100% 100%;
            background-repeat: no-repeat;
            transform: skew(-8deg);
        }

        /* Animations au scroll */
        .scroll-animate {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .scroll-animate.animate-in-view {
            opacity: 1;
            transform: translateY(0);
        }

        /* Animations pour les cartes d'annonces */
        .listing-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .listing-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.2);
        }

        /* Animations pour les boutons */
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        /* Animation pour les modals */
        .modal-enter {
            animation: modalFadeIn 0.3s ease-out;
        }

        .modal-leave {
            animation: modalFadeOut 0.3s ease-in;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes modalFadeOut {
            from {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            to {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }
        }

        /* Animation pour les images au hover */
        .image-hover-zoom {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .image-hover-zoom:hover {
            transform: scale(1.1);
        }

        /* Animation pour les icônes */
        .icon-rotate {
            transition: transform 0.3s ease;
        }

        .icon-rotate:hover {
            transform: rotate(360deg);
        }

        /* Gradient animé pour les boutons */
        @keyframes gradient-shift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .gradient-animated {
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
        }

        /* Amélioration des transitions globales */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        /* Responsive améliorations */
        @media (max-width: 640px) {
            .animate-fade-in-up,
            .animate-fade-in-up-delay,
            .animate-fade-in-up-delay-2 {
                animation-duration: 0.6s;
            }
        }

        /* Animation pour le header */
        .header-smooth {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
        }

        /* Pulse animation pour les éléments importants */
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(243, 164, 62, 0.7);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(243, 164, 62, 0);
            }
        }

        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }
        </style>
    </head>
<body class="font-sans antialiased bg-white" x-data="alpineData">
    <!-- HEADER -->
    <x-header />

    <!-- SECTION HERO / BANNIÈRE -->
    <section id="accueil" class="relative min-h-[600px] md:h-screen overflow-hidden">
        <!-- Image de fond animée -->
        <div class="absolute inset-0 banner-bg-animated" style="background-image: url('{{ asset('images/banniere.jpg') }}'); background-size: cover; background-position: center top; background-repeat: no-repeat;"></div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/50"></div>

        <!-- Contenu Centré -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-[600px] md:h-screen flex flex-col justify-between items-center text-center py-8 md:py-0">
            <!-- Titre et Sous-titre -->
            <div class="flex-1 flex flex-col justify-center items-center pt-8 md:pt-0">
                <!-- Titre -->
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 max-w-5xl animate-fade-in-up">
                    Trouvez votre futur chez-vous
                </h1>

                <!-- Sous-titre -->
                <p class="text-xl sm:text-2xl md:text-3xl text-white/90 mb-8 md:mb-12 max-w-3xl animate-fade-in-up-delay">
                    L'immobilier en toute confiance avec AT Logement.
                </p>
            </div>

            <!-- Barre de Recherche dans la Bannière (en bas) -->
            <div class="w-full max-w-4xl md:pb-16 animate-fade-in-up-delay-2" style="padding-bottom: calc(3rem + 1.5cm);">
                <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-2xl p-3 sm:p-4 md:p-5">
                    <form action="{{ route('listings.search') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                        <!-- Champ de recherche principal -->
                        <div class="flex-1 relative">
                            <input
                                type="text"
                                name="query"
                                placeholder="Rechercher un bien, un quartier, une ville..."
                                class="w-full px-4 py-3 pl-12 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300"
                            >
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <!-- Bouton Rechercher -->
                        <button type="submit" class="btn-primary bg-at-orange text-white px-8 py-3 rounded-lg font-bold hover:bg-at-orange-600 hover:shadow-at-orange active:scale-95 transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Rechercher</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main>

        <!-- Section Filtres (apparaît après recherche) -->
        <section
            x-show="showFilters"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4"
            class="bg-white border-b border-gray-200 shadow-sm"
            style="display: none;"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtrer les résultats</h3>
                <form @submit.prevent="applyFilters()" class="space-y-4">
                    <!-- Ligne 1: Filtres principaux -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Localisation -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Localisation</label>
                            <select x-model="filters.location" class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300">
                                <option value="">Toutes les villes</option>
                                <option value="conakry">Conakry</option>
                                <option value="kindia">Kindia</option>
                                <option value="kankan">Kankan</option>
                                <option value="nzerekore">Nzérékoré</option>
                                <option value="labé">Labé</option>
                            </select>
                        </div>

                        <!-- Type de bien -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Type de bien</label>
                            <select x-model="filters.type" class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300">
                                <option value="">Tous les types</option>
                                <option value="maison">Maison</option>
                                <option value="appartement">Appartement</option>
                                <option value="villa">Villa</option>
                                <option value="terrain">Terrain</option>
                                <option value="bureau">Bureau</option>
                                <option value="commerce">Local commercial</option>
                            </select>
                        </div>

                        <!-- Transaction -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction</label>
                            <select x-model="filters.transaction" class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300">
                                <option value="">Vente/Location</option>
                                <option value="vente">Vente</option>
                                <option value="location">Location</option>
                            </select>
                        </div>

                        <!-- Nombre de chambres -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Chambres</label>
                            <select x-model="filters.bedrooms" class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300">
                                <option value="">Toutes</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                                <option value="5">5+</option>
                            </select>
                        </div>
                    </div>

                    <!-- Ligne 2: Budget -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Budget Min -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Budget Min (GNF)</label>
                            <input
                                type="number"
                                x-model="filters.budgetMin"
                                placeholder="Ex: 5000000"
                                class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300"
                            >
                        </div>

                        <!-- Budget Max -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Budget Max (GNF)</label>
                            <input
                                type="number"
                                x-model="filters.budgetMax"
                                placeholder="Ex: 20000000"
                                class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 hover:border-orange-300"
                            >
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="showFilters = false"
                            class="px-6 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-300"
                        >
                            Fermer
                        </button>
                        <button
                            type="submit"
                            class="btn-primary px-6 py-2 bg-at-orange text-white rounded-lg font-semibold hover:bg-at-orange-600 hover:shadow-at-orange active:scale-95 transition-all duration-300"
                        >
                            Appliquer les filtres
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- SECTION ANNONCES (Dernières Opportunités) -->
        <section id="annonces" class="py-20 bg-gradient-to-b from-gray-50 to-white scroll-animate">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 scroll-animate">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-8">
                        <span class="double-underline">Dernières Opportunités</span>
                    </h2>
                </div>

                <!-- Message si aucun résultat -->
                @if($listings->isEmpty())
                    <div class="text-center py-12 scroll-animate">
                        <p class="text-gray-500 text-lg">Aucune annonce disponible pour le moment.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($listings as $index => $listing)
                            <div class="scroll-animate" style="animation-delay: {{ $index * 0.1 }}s;">
                                <x-listing-card :listing="$listing" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <!-- Modal Détails Annonce -->
        <div
            x-show="showAnnouncementModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.away="closeAnnouncement()"
            @keydown.escape.window="closeAnnouncement()"
            class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
            style="display: none;"
        >
            <div
                x-show="showAnnouncementModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                @click.stop
                class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative"
                style="display: none;"
            >
                <template x-if="currentAnnouncement">
                    <div class="relative">
                        <!-- Header avec titre et bouton fermer -->
                        <div class="sticky top-0 bg-white z-10 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900" x-text="currentAnnouncement.title"></h2>
                            <button @click="closeAnnouncement()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Contenu -->
                        <div class="p-6">
                            <!-- Prix et Durée -->
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <span class="inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white text-lg font-bold px-5 py-2 rounded-full shadow-md" x-text="currentAnnouncement.price"></span>
                                <span class="text-sm text-gray-500 whitespace-nowrap" x-text="getTimeAgo(currentAnnouncement.publishedAt)"></span>
                            </div>

                            <!-- Description courte -->
                            <p class="text-gray-600 mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span x-text="currentAnnouncement.description"></span>
                            </p>

                            <!-- Carrousel d'images -->
                            <div class="relative mb-6 rounded-xl overflow-hidden">
                                <div class="relative h-64 md:h-96 bg-gray-100">
                                    <img
                                        :src="currentAnnouncement.images[currentImageIndex]"
                                        :alt="currentAnnouncement.title"
                                        class="w-full h-full object-cover"
                                    >

                                    <!-- Boutons navigation -->
                                    <button
                                        x-show="currentImageIndex > 0"
                                        @click="prevImage()"
                                        class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full p-2 shadow-lg transition-all"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>

                                    <button
                                        x-show="currentImageIndex < currentAnnouncement.images.length - 1"
                                        @click="nextImage()"
                                        class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full p-2 shadow-lg transition-all"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>

                                    <!-- Indicateurs -->
                                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                                        <template x-for="(image, index) in currentAnnouncement.images" :key="index">
                                            <button
                                                @click="currentImageIndex = index"
                                                class="w-2 h-2 rounded-full transition-all"
                                                :class="currentImageIndex === index ? 'bg-white w-6' : 'bg-white/50'"
                                            ></button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Description complète -->
                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Description</h3>
                                <p class="text-gray-700 leading-relaxed" x-text="currentAnnouncement.fullDescription"></p>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                                <!-- Bouton Envoyer un message / Message envoyé -->
                                <div class="flex-1">
                                    <button
                                        x-show="!messageSent"
                                        @click="openMessageForm()"
                                        class="w-full bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        Envoyer un message
                                    </button>
                                    <div
                                        x-show="messageSent"
                                        class="w-full bg-green-500 text-white px-6 py-3 rounded-lg font-semibold flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Message envoyé
                                    </div>
                                </div>

                                <!-- Bouton Appeler -->
                                <a
                                    :href="`tel:${currentAnnouncement.phone}`"
                                    class="flex-1 bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600 hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Appeler
                                </a>
                            </div>

                            <!-- Réseaux sociaux -->
                            <div class="mb-6 pt-6 border-t border-gray-200">
                                <p class="text-gray-700 font-semibold mb-4">Voir l'annonce sur</p>
                                <div class="flex items-center gap-4">
                                    <template x-for="network in currentAnnouncement.socialNetworks" :key="network">
                                        <a
                                            href="#"
                                            class="flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 hover:bg-orange-500 text-gray-600 hover:text-white transition-all duration-300"
                                            :title="network.charAt(0).toUpperCase() + network.slice(1)"
                                        >
                                            <template x-if="network === 'facebook'">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                            </template>
                                            <template x-if="network === 'instagram'">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                </svg>
                                            </template>
                                            <template x-if="network === 'whatsapp'">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                                </svg>
                                            </template>
                                            <template x-if="network === 'twitter'">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                </svg>
                                            </template>
                                        </a>
                                    </template>
                                </div>
                            </div>

                            <!-- Bouton Retour -->
                            <div class="pt-4 border-t border-gray-200">
                                <button
                                    @click="closeAnnouncement()"
                                    class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 hover:shadow-lg transition-all duration-300"
                                >
                                    Retour
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Modal Détails Listing -->
        <div
            x-show="showListingModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click.away="closeListing()"
            @keydown.escape.window="closeListing()"
            class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
            style="display: none;"
        >
            <div
                x-show="showListingModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                @click.stop
                class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative"
                style="display: none;"
            >
                <template x-if="currentListing">
                    <div class="relative">
                        <!-- Header avec titre et bouton fermer -->
                        <div class="sticky top-0 bg-white z-10 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900" x-text="currentListing.title"></h2>
                            <button @click="closeListing()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Contenu -->
                        <div class="p-6">
                            <!-- Prix -->
                            <template x-if="currentListing.price">
                                <div class="mb-4">
                                    <span class="inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white text-lg font-bold px-5 py-2 rounded-full shadow-md">
                                        <span x-text="new Intl.NumberFormat('fr-FR').format(currentListing.price)"></span> <span x-text="currentListing.currency || 'GNF'"></span>
                                    </span>
                                </div>
                            </template>

                            <!-- Adresse -->
                            <template x-if="currentListing.address || currentListing.city">
                                <p class="text-gray-600 mb-6 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span x-text="(currentListing.address || '') + (currentListing.address && currentListing.city ? ', ' : '') + (currentListing.city || '')"></span>
                                </p>
                            </template>

                            <!-- Carrousel d'images -->
                            <template x-if="currentListing.imageUrls && currentListing.imageUrls.length > 0">
                                <div class="relative mb-6 rounded-xl overflow-hidden">
                                    <div class="relative h-64 md:h-80 bg-gray-100">
                                        <img
                                            :src="getListingImage()"
                                            :alt="currentListing.title"
                                            class="w-full h-full object-cover"
                                        >

                                        <!-- Boutons navigation -->
                                        <button
                                            x-show="currentImageIndex > 0"
                                            @click="prevImage()"
                                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full p-2 shadow-lg transition-all"
                                        >
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>

                                        <button
                                            x-show="currentListing.imageUrls && currentImageIndex < currentListing.imageUrls.length - 1"
                                            @click="nextImage()"
                                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 rounded-full p-2 shadow-lg transition-all"
                                        >
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>

                                        <!-- Indicateurs -->
                                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
                                            <template x-for="(image, index) in currentListing.imageUrls" :key="index">
                                                <button
                                                    @click="currentImageIndex = index"
                                                    class="w-2 h-2 rounded-full transition-all"
                                                    :class="currentImageIndex === index ? 'bg-white w-6' : 'bg-white/50'"
                                                ></button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Description complète -->
                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Description</h3>
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line" x-text="currentListing.description"></p>
                            </div>

                            <!-- Caractéristiques -->
                            <template x-if="currentListing.type === 'residential'">
                                <div class="mb-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <template x-if="currentListing.bedrooms">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                            <span x-text="currentListing.bedrooms + ' chambre' + (currentListing.bedrooms > 1 ? 's' : '')"></span>
                                        </div>
                                    </template>
                                    <template x-if="currentListing.bathrooms">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                            <span x-text="currentListing.bathrooms + ' salle' + (currentListing.bathrooms > 1 ? 's' : '') + ' de bain'"></span>
                                        </div>
                                    </template>
                                    <template x-if="currentListing.surface">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                            </svg>
                                            <span x-text="new Intl.NumberFormat('fr-FR').format(currentListing.surface) + ' m²'"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Bouton Favoris (si utilisateur connecté) -->
                            @auth
                            <div class="mb-4">
                                <button
                                    x-bind:id="'favorite-btn-' + currentListing.id"
                                    @click="toggleFavorite(currentListing.id)"
                                    x-bind:class="isFavorite ? 'bg-red-500 hover:bg-red-600' : 'bg-gray-200 hover:bg-gray-300'"
                                    class="w-full text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"
                                >
                                    <svg x-show="!isFavorite" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <svg x-show="isFavorite" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span x-text="isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris'"></span>
                                </button>
                            </div>
                            @endauth

                            <!-- Boutons d'action -->
                            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                                <!-- Bouton Envoyer un message / Message envoyé -->
                                <div class="flex-1">
                                    <button
                                        x-show="!messageSent"
                                        @click="openMessageForm()"
                                        class="w-full bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        Envoyer un message
                                    </button>
                                    <div
                                        x-show="messageSent"
                                        class="w-full bg-green-500 text-white px-6 py-3 rounded-lg font-semibold flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Message envoyé
                                    </div>
                                </div>

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
                            <div 
                                x-show="currentListing && currentListing.social_links && (currentListing.social_links.facebook || currentListing.social_links.linkedin || currentListing.social_links.twitter || currentListing.social_links.instagram || currentListing.social_links.tiktok)"
                                x-cloak
                                class="mb-6 pt-6 border-t border-gray-200"
                            >
                                <p class="text-sm text-gray-500 text-center mb-4">Voir l'annonce sur :</p>
                                <div class="flex justify-center items-center gap-4 flex-wrap">
                                    <!-- Facebook -->
                                    <a
                                        x-show="currentListing.social_links && currentListing.social_links.facebook"
                                        :href="currentListing.social_links.facebook"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style="background-color: #2563eb;"
                                        class="w-12 h-12 rounded-full flex items-center justify-center hover:opacity-90 transition-opacity shadow-md hover:shadow-lg"
                                        title="Voir sur Facebook"
                                    >
                                        <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>

                                    <!-- LinkedIn -->
                                    <a
                                        x-show="currentListing.social_links && currentListing.social_links.linkedin"
                                        :href="currentListing.social_links.linkedin"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style="background-color: #1e40af;"
                                        class="w-12 h-12 rounded-full flex items-center justify-center hover:opacity-90 transition-opacity shadow-md hover:shadow-lg"
                                        title="Voir sur LinkedIn"
                                    >
                                        <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>

                                    <!-- Twitter/X -->
                                    <a
                                        x-show="currentListing.social_links && currentListing.social_links.twitter"
                                        :href="currentListing.social_links.twitter"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style="background-color: #000000;"
                                        class="w-12 h-12 rounded-full flex items-center justify-center hover:opacity-90 transition-opacity shadow-md hover:shadow-lg"
                                        title="Voir sur X (Twitter)"
                                    >
                                        <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>

                                    <!-- Instagram -->
                                    <a
                                        x-show="currentListing.social_links && currentListing.social_links.instagram"
                                        :href="currentListing.social_links.instagram"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);"
                                        class="w-12 h-12 rounded-full flex items-center justify-center hover:opacity-90 transition-opacity shadow-md hover:shadow-lg"
                                        title="Voir sur Instagram"
                                    >
                                        <svg class="w-5 h-5" fill="white" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>

                                    <!-- TikTok -->
                                    <a
                                        x-show="currentListing.social_links && currentListing.social_links.tiktok"
                                        :href="currentListing.social_links.tiktok"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style="background-color: #000000;"
                                        class="w-12 h-12 rounded-full flex items-center justify-center hover:opacity-90 transition-opacity shadow-md hover:shadow-lg"
                                        title="Voir sur TikTok"
                                    >
                                        <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
                                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Bouton Retour -->
                            <div class="pt-4 border-t border-gray-200">
                                <button
                                    @click="closeListing()"
                                    class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 hover:shadow-lg transition-all duration-300"
                                >
                                    Retour
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

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
                class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative"
                style="display: none;"
            >
                <div class="p-6">
                    <!-- Header du formulaire -->
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-900">Envoyer un message</h3>
                        <button
                            @click="closeMessageForm()"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Formulaire -->
                    <form @submit.prevent="sendMessage()" x-ref="messageFormScrollable">
                        <!-- Nom -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nom complet *</label>
                            <input
                                type="text"
                                x-model="messageForm.name"
                                required
                                placeholder="Votre nom complet"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300"
                            >
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input
                                type="email"
                                x-model="messageForm.email"
                                required
                                placeholder="votre.email@exemple.com"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300"
                            >
                                </div>

                        <!-- Téléphone -->
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone *</label>
                            <input
                                type="tel"
                                x-model="messageForm.phone"
                                required
                                placeholder="+224 XXX XXX XXX"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300"
                            >
                                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Message personnalisé</label>
                            <textarea
                                x-model="messageForm.message"
                                rows="6"
                                placeholder="Écrivez votre message ici..."
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all duration-300 resize-none"
                            ></textarea>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex gap-3" style="margin-bottom: 1cm;">
                            <button
                                type="button"
                                @click="closeMessageForm()"
                                class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-300"
                            >
                                Annuler
                            </button>
                            <button
                                type="submit"
                                :disabled="isSendingMessage"
                                :class="isSendingMessage ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-600 hover:shadow-lg'"
                                class="flex-1 px-6 py-3 bg-orange-500 text-white rounded-lg font-semibold transition-all duration-300"
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

        <!-- SECTION SERVICES (Nos Services) -->
        <section id="services" class="py-20 bg-white services-section scroll-animate">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-8">
                        <span class="double-underline">Nos Services</span>
                    </h2>
                                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Service 1: Locations de biens immobiliers -->
                    <div class="service-card bg-[#86c14f] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#f3a43e] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">Locations de biens immobiliers</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Trouvez le bien locatif idéal parmi notre large sélection de propriétés.</p>
                    </div>

                    <!-- Service 2: Ventes de biens immobiliers -->
                    <div class="service-card bg-[#f3a43e] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#352f30] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors duration-300">Ventes de biens immobiliers</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Achetez votre bien immobilier avec notre accompagnement professionnel.</p>
                    </div>

                    <!-- Service 3: Promotion immobilière -->
                    <div class="service-card bg-[#726961] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#87c04f] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors duration-300">Promotion immobilière</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Développement et commercialisation de projets immobiliers neufs.</p>
                    </div>

                    <!-- Service 4: Etat des lieux -->
                    <div class="service-card bg-[#86c14f] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#f3a43e] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors duration-300">Etat des lieux</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Réalisation d'états des lieux d'entrée et de sortie détaillés et professionnels.</p>
                    </div>

                    <!-- Service 5: Gestion de biens immobiliers -->
                    <div class="service-card bg-[#f3a43e] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#352f30] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors duration-300">Gestion de biens immobiliers</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Gestion complète de votre patrimoine immobilier : location, entretien, suivi.</p>
                    </div>

                    <!-- Service 6: Elaboration de contrat de location -->
                    <div class="service-card bg-[#726961] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#87c04f] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-teal-600 transition-colors duration-300">Elaboration de contrat de location</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Rédaction de contrats de location conformes à la législation en vigueur.</p>
                    </div>

                    <!-- Service 7: Conseil Immobilier -->
                    <div class="service-card bg-[#86c14f] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#f3a43e] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-amber-600 transition-colors duration-300">Conseil Immobilier</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Conseils experts pour tous vos projets immobiliers et investissements.</p>
                    </div>

                    <!-- Service 8: Rénovation et achèvement des biens immobiliers -->
                    <div class="service-card bg-[#f3a43e] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#352f30] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-red-600 transition-colors duration-300">Rénovation et achèvement</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Travaux de rénovation et d'achèvement pour valoriser votre bien immobilier.</p>
                    </div>

                    <!-- Service 9: Service de nettoyage -->
                    <div class="service-card bg-[#726961] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#87c04f] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-emerald-600 transition-colors duration-300">Service de nettoyage</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Nettoyage professionnel pour préparer et entretenir vos biens immobiliers.</p>
                    </div>

                    <!-- Service 10: Service de transport -->
                    <div class="service-card bg-[#86c14f] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#f3a43e] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-cyan-600 transition-colors duration-300">Service de transport</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Solutions de transport et déménagement pour vos besoins immobiliers.</p>
                    </div>

                    <!-- Service 11: Frigoriste-SOS-24/7 -->
                    <div class="service-card bg-[#f3a43e] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#352f30] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-500 transition-colors duration-300">Frigoriste-SOS-24/7</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Dépannage frigoriste d'urgence disponible 24h/24 et 7j/7.</p>
                    </div>

                    <!-- Service 12: Plomberie-SOS-24/7 -->
                    <div class="service-card bg-[#726961] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#87c04f] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-red-700 transition-colors duration-300">Plomberie-SOS-24/7</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Intervention plomberie d'urgence disponible 24h/24 et 7j/7.</p>
                    </div>

                    <!-- Service 13: Electricité-SOS-24/7 -->
                    <div class="service-card bg-[#86c14f] rounded-2xl p-8 text-center hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-gray-100 group">
                        <div class="w-20 h-20 bg-[#f3a43e] rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-yellow-600 transition-colors duration-300">Electricité-SOS-24/7</h3>
                        <p class="text-white font-bold text-sm leading-relaxed">Dépannage électrique d'urgence disponible 24h/24 et 7j/7.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION CONFIANCE (Présentation) -->
        <section id="confiance" class="py-20 bg-gradient-to-b from-white to-gray-50 scroll-animate">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Image (Mobile: en haut, Desktop: à gauche) -->
                    <div class="order-1 lg:order-1">
                        <div class="rounded-2xl overflow-hidden shadow-2xl hover:shadow-3xl transition-all duration-300 group">
                            <img
                                src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=2073"
                                alt="Équipe AT Logement"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                                </div>

                    <!-- Texte (Mobile: en bas, Desktop: à droite) -->
                    <div class="order-2 lg:order-2">
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Pourquoi choisir AT Logement ?</h2>
                        <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                            Avec plus de 10 ans d'expérience sur le marché immobilier guinéen, AT Logement est votre partenaire de confiance pour tous vos projets immobiliers.
                        </p>
                        <ul class="space-y-5">
                            <li class="flex items-start gap-4 group">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-orange-600 transition-colors duration-300 mt-0.5">
                                    <svg class="w-5 h-5 text-orange-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 text-lg group-hover:text-gray-900 transition-colors duration-300">Expertise locale approfondie du marché guinéen</span>
                            </li>
                            <li class="flex items-start gap-4 group">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-orange-600 transition-colors duration-300 mt-0.5">
                                    <svg class="w-5 h-5 text-orange-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 text-lg group-hover:text-gray-900 transition-colors duration-300">Accompagnement personnalisé à chaque étape</span>
                            </li>
                            <li class="flex items-start gap-4 group">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-orange-600 transition-colors duration-300 mt-0.5">
                                    <svg class="w-5 h-5 text-orange-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 text-lg group-hover:text-gray-900 transition-colors duration-300">Portfolio varié de biens sélectionnés avec soin</span>
                            </li>
                            <li class="flex items-start gap-4 group">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-orange-600 transition-colors duration-300 mt-0.5">
                                    <svg class="w-5 h-5 text-orange-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 text-lg group-hover:text-gray-900 transition-colors duration-300">Transparence totale dans toutes les transactions</span>
                            </li>
                        </ul>
                                </div>
                            </div>
                        </div>
        </section>
                    </main>

    <!-- FOOTER -->
    <footer class="bg-gradient-to-b from-gray-900 to-black text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 text-center">
                <!-- Infos -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center justify-center space-x-2 mb-6">
                        <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="text-2xl font-bold">AT Logement</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-6 leading-relaxed">
                        Votre partenaire de confiance pour tous vos projets immobiliers en Guinée.
                    </p>
                    <p class="text-gray-400 text-sm flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <strong class="text-white">+224 612 345 678</strong>
                    </p>
                </div>

                <!-- Liens rapides -->
                <div>
                    <h4 class="font-bold text-lg mb-6 text-white">Liens rapides</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li><a href="#accueil" class="hover:text-orange-400 hover:translate-x-1 transition-all duration-300 inline-block">Accueil</a></li>
                        <li><a href="#vente" class="hover:text-orange-400 hover:translate-x-1 transition-all duration-300 inline-block">Vente</a></li>
                        <li><a href="#location" class="hover:text-orange-400 hover:translate-x-1 transition-all duration-300 inline-block">Location</a></li>
                        <li><a href="#contact" class="hover:text-orange-400 hover:translate-x-1 transition-all duration-300 inline-block">Contact</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="font-bold text-lg mb-6 text-white">Services</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li><a href="#services" class="hover:text-orange-400 hover:translate-x-1 transition-all duration-300 inline-block">Transaction</a></li>
                        <li><a href="#sos" class="hover:text-orange-400 hover:translate-x-1 transition-all duration-300 inline-block">SOS Dépannage 24/7</a></li>
                        <li><a href="#nettoyage" class="hover:text-green-400 hover:translate-x-1 transition-all duration-300 inline-block">Nettoyage Pro</a></li>
                        <li><a href="#demenagement" class="hover:text-orange-400 hover:translate-x-1 transition-all duration-300 inline-block">Déménagement</a></li>
                    </ul>
                </div>

                <!-- Contact/Réseaux -->
                <div>
                    <h4 class="font-bold text-lg mb-6 text-white">Contact & Réseaux</h4>
                    <ul class="space-y-4 text-gray-400 text-sm">
                        <li>
                            <a href="mailto:contact@atimmobilier.com" class="hover:text-orange-400 transition-colors duration-300 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                contact@atimmobilier.com
                            </a>
                        </li>
                        <li class="flex items-center justify-center gap-3">
                            <a href="#" class="hover:text-orange-500 transition-colors duration-300 hover:scale-110 inline-block">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="hover:text-orange-500 transition-colors duration-300 hover:scale-110 inline-block">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="hover:text-pink-400 transition-colors duration-300 hover:scale-110 inline-block">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} AT Logement. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    <script>
        // Données Alpine.js - Définir avant Alpine.js
        window.alpineData = {
    showFilters: false,
    showAnnouncementModal: false,
    showListingModal: false,
    showMessageForm: false,
    showSuccessModal: false,
    showErrorModal: false,
    errorMessage: '',
    messageSent: false,
    isSendingMessage: false,
    currentAnnouncement: null,
    currentListing: null,
    currentImageIndex: 0,
    isFavorite: false,
    messageForm: {
        name: '',
        email: '',
        phone: '',
        message: ''
    },
    searchQuery: '',
    filters: {
        location: '',
        type: '',
        transaction: '',
        bedrooms: '',
        budgetMin: '',
        budgetMax: ''
    },
    announcements: [
        {
            id: 1,
            title: 'Villa moderne à Conakry',
            description: 'Quartier Camayenne, Conakry',
            fullDescription: "Magnifique villa moderne située dans le quartier de Camayenne à Conakry. Cette propriété de standing comprend 4 chambres spacieuses, 3 salles de bain modernes, un salon lumineux, une cuisine équipée et un jardin paysager. Idéale pour une famille en quête de confort et d'espace.",
            price: '45 000 000 GNF',
            priceNumber: 45000000,
            type: 'villa',
            location: 'conakry',
            bedrooms: 4,
            bathrooms: 3,
            transaction: 'vente',
            publishedAt: new Date(Date.now() - 2 * 60 * 1000),
            images: [
                'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075',
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=2053',
                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?q=80&w=2070'
            ],
            socialNetworks: ['facebook', 'whatsapp', 'twitter'],
            phone: '+224 612 345 678'
        },
        {
            id: 2,
            title: 'Appartement spacieux',
            description: 'Quartier Almamya, Conakry',
            fullDescription: "Appartement spacieux et moderne dans le quartier d'Almamya. Composé de 3 chambres, 2 salles de bain, un salon-salle à manger, une cuisine moderne et un balcon avec vue. Situé dans un immeuble sécurisé avec parking.",
            price: '25 000 000 GNF',
            priceNumber: 25000000,
            type: 'appartement',
            location: 'conakry',
            bedrooms: 3,
            bathrooms: 2,
            transaction: 'location',
            publishedAt: new Date(Date.now() - (1 * 30 + 17) * 24 * 60 * 60 * 1000),
            images: [
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=2053',
                'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075',
                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?q=80&w=2070'
            ],
            socialNetworks: ['facebook', 'whatsapp'],
            phone: '+224 623 456 789'
        },
        {
            id: 3,
            title: 'Villa de luxe avec piscine',
            description: 'Quartier Dixinn, Conakry',
            fullDescription: 'Somptueuse villa de luxe avec piscine dans le prestigieux quartier de Dixinn. Cette propriété exceptionnelle dispose de 5 chambres, 4 salles de bain, plusieurs salons, une cuisine professionnelle, un jardin paysager et une piscine privée. Prestige et élégance garantis.',
            price: '60 000 000 GNF',
            priceNumber: 60000000,
            type: 'villa',
            location: 'conakry',
            bedrooms: 5,
            bathrooms: 4,
            transaction: 'vente',
            publishedAt: new Date(Date.now() - 14 * 24 * 60 * 60 * 1000),
            images: [
                'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?q=80&w=2070',
                'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075',
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?q=80&w=2053'
            ],
            socialNetworks: ['facebook', 'instagram', 'whatsapp', 'twitter'],
            phone: '+224 634 567 890'
        }
    ],
    get filteredAnnouncements() {
        let results = this.announcements;
        if (this.searchQuery.trim() !== '') {
            const query = this.searchQuery.toLowerCase();
            results = results.filter(announcement =>
                announcement.title.toLowerCase().includes(query) ||
                announcement.description.toLowerCase().includes(query) ||
                announcement.fullDescription.toLowerCase().includes(query)
            );
        }
        if (this.filters.location !== '') {
            results = results.filter(a => a.location === this.filters.location);
        }
        if (this.filters.type !== '') {
            results = results.filter(a => a.type === this.filters.type);
        }
        if (this.filters.transaction !== '') {
            results = results.filter(a => a.transaction === this.filters.transaction);
        }
        if (this.filters.bedrooms !== '' && this.filters.bedrooms !== null) {
            const minBedrooms = parseInt(this.filters.bedrooms);
            if (!isNaN(minBedrooms)) {
                results = results.filter(a => a.bedrooms >= minBedrooms);
            }
        }
        if (this.filters.budgetMin !== '' && this.filters.budgetMin !== null) {
            const minBudget = parseInt(this.filters.budgetMin);
            if (!isNaN(minBudget)) {
                results = results.filter(a => a.priceNumber >= minBudget);
            }
        }
        if (this.filters.budgetMax !== '' && this.filters.budgetMax !== null) {
            const maxBudget = parseInt(this.filters.budgetMax);
            if (!isNaN(maxBudget)) {
                results = results.filter(a => a.priceNumber <= maxBudget);
            }
        }
        return results;
    },
    performSearch() {
        this.showFilters = true;
        setTimeout(() => {
            document.getElementById('annonces')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    },
    applyFilters() {
        setTimeout(() => {
            document.getElementById('annonces')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    },
    getTimeAgo(publishedDate) {
        if (!publishedDate) return '';
        const now = new Date();
        const diff = now - publishedDate;
        const minutes = Math.floor(diff / (1000 * 60));
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const months = Math.floor(days / 30);
        const remainingDays = days % 30;
        if (minutes < 60) {
            return "Il y a " + minutes + " minute" + (minutes > 1 ? "s" : "");
        } else if (hours < 24) {
            return "Il y a " + hours + " heure" + (hours > 1 ? "s" : "");
        } else if (days < 30) {
            return "Il y a " + days + " jour" + (days > 1 ? "s" : "");
        } else if (months >= 1 && remainingDays === 0) {
            return "Il y a " + months + " Mois";
        } else if (months >= 1 && remainingDays > 0) {
            return "Il y a " + months + " Mois " + remainingDays + " jour" + (remainingDays > 1 ? "s" : "");
        } else {
            return "Il y a " + days + " jour" + (days > 1 ? "s" : "");
        }
    },
    openAnnouncement(id) {
        this.currentAnnouncement = this.announcements.find(a => a.id === id);
        this.currentImageIndex = 0;
        this.showAnnouncementModal = true;
        this.messageSent = false;
        this.showMessageForm = false;
        // Ne pas réinitialiser les champs si l'utilisateur est connecté
        if (!this.messageForm.name || !this.messageForm.email) {
            this.messageForm = { 
                name: @json($userData['name'] ?? ''), 
                email: @json($userData['email'] ?? ''), 
                phone: @json($userData['phone'] ?? ''), 
                message: '' 
            };
        } else {
            this.messageForm.message = '';
        }
        document.body.style.overflow = 'hidden';
    },
    closeAnnouncement() {
        this.showAnnouncementModal = false;
        this.showMessageForm = false;
        this.messageSent = false;
        document.body.style.overflow = '';
    },
    openListingModal(listingData) {
        // Alias pour compatibilité avec les composants
        this.openListing(listingData);
    },
    async openListing(listingData) {
        if (!listingData.imageUrls && listingData.images && listingData.images.length > 0) {
            listingData.imageUrls = listingData.images.map(img => {
                if (typeof img === 'string' && (img.startsWith('http') || img.startsWith('/'))) {
                    return img;
                }
                const imgPath = typeof img === 'string' ? img : '';
                return imgPath;
            });
        }
        this.currentListing = listingData;
        this.currentImageIndex = 0;
        this.showListingModal = true;
        this.messageSent = false;
        this.showMessageForm = false;
        // Ne pas réinitialiser les champs si l'utilisateur est connecté, garder les valeurs pré-remplies
        if (!this.messageForm.name || !this.messageForm.email) {
            this.messageForm = { 
                name: @json($userData['name'] ?? ''), 
                email: @json($userData['email'] ?? ''), 
                phone: @json($userData['phone'] ?? ''), 
                message: '' 
            };
        } else {
            // Garder les valeurs existantes mais réinitialiser le message
            this.messageForm.message = '';
        }
        
        // Vérifier si l'annonce est en favoris (si utilisateur connecté)
        @if(auth()->check())
            await this.checkFavorite(listingData.id);
        @endif
        
        document.body.style.overflow = 'hidden';
    },
    async checkFavorite(listingId) {
        try {
            const response = await fetch(`/listings/${listingId}/favorite/check`);
            const data = await response.json();
            this.isFavorite = data.is_favorite || false;
        } catch (error) {
            console.error('Erreur lors de la vérification des favoris:', error);
            this.isFavorite = false;
        }
    },
    async toggleFavorite(listingId) {
        @if(!auth()->check())
            // Rediriger vers la page de connexion si non connecté
            window.location.href = '/login';
            return;
        @endif
        
        try {
            const url = this.isFavorite 
                ? `/listings/${listingId}/favorite`
                : `/listings/${listingId}/favorite`;
            const method = this.isFavorite ? 'DELETE' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.isFavorite = data.is_favorite;
            } else {
                console.error('Erreur:', data.message);
            }
        } catch (error) {
            console.error('Erreur lors de l\'ajout/retrait des favoris:', error);
        }
    },
    closeListing() {
        this.showListingModal = false;
        this.showMessageForm = false;
        this.messageSent = false;
        document.body.style.overflow = '';
    },
    async openMessageForm() {
        // Vérifier si l'utilisateur est connecté et récupérer ses données uniquement à ce moment
        // Ne pas pré-remplir avec des données statiques pour éviter les problèmes de sécurité
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
            const modalContainer = document.querySelector('.bg-white.rounded-2xl.shadow-2xl.max-w-4xl');
            if (modalContainer) {
                modalContainer.scrollTop = 0;
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
            this.isSendingMessage = false;
            this.showMessageForm = false;
            setTimeout(() => {
                this.showErrorModal = true;
                document.body.style.overflow = 'hidden';
            }, 150);
            return;
        }
        
        // Désactiver le bouton et afficher l'état de chargement
        this.isSendingMessage = true;
        
        const formData = {
            listing_id: this.currentListing ? this.currentListing.id : null,
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
                // Réinitialiser l'état d'envoi avant de fermer le formulaire
                this.isSendingMessage = false;
                // Fermer d'abord le formulaire de message
                this.showMessageForm = false;
                // Réinitialiser seulement le message, garder les autres infos pour la prochaine fois
                this.messageForm.message = '';
                // Fermer aussi le modal de listing pour que le modal de succès soit au premier plan
                this.showListingModal = false;
                // Afficher le modal de succès après un court délai pour s'assurer que les autres modals sont fermés
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
                // Afficher le modal d'erreur
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
            // Afficher le modal d'erreur
            this.errorMessage = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
            this.isSendingMessage = false;
            this.showMessageForm = false;
            setTimeout(() => {
                this.showErrorModal = true;
                document.body.style.overflow = 'hidden';
            }, 150);
        }
    },
    nextImage() {
        if (this.currentAnnouncement && this.currentImageIndex < this.currentAnnouncement.images.length - 1) {
            this.currentImageIndex++;
        } else if (this.currentListing && this.currentListing.imageUrls && this.currentImageIndex < this.currentListing.imageUrls.length - 1) {
            this.currentImageIndex++;
        }
    },
    prevImage() {
        if (this.currentImageIndex > 0) {
            this.currentImageIndex--;
        }
    },
    getListingImage() {
        if (this.currentListing && this.currentListing.imageUrls && this.currentListing.imageUrls[this.currentImageIndex]) {
            return this.currentListing.imageUrls[this.currentImageIndex];
        }
        return '';
    }
};

        // Fonction globale pour ouvrir le modal de listing depuis les composants
        function openListingModal(listing) {
            // Attendre que Alpine soit chargé
            if (typeof window.Alpine === 'undefined') {
                // Si Alpine n'est pas encore chargé, attendre
                document.addEventListener('alpine:init', () => {
                    openListingModal(listing);
                });
                return;
            }
            
            // Trouver le composant Alpine.js sur la page
            const alpineElement = document.querySelector('[x-data]');
            if (alpineElement && window.Alpine) {
                try {
                    const alpineData = window.Alpine.$data(alpineElement);
                    if (alpineData && typeof alpineData.openListing === 'function') {
                        alpineData.openListing(listing);
                    } else {
                        console.error('openListing method not found or is not a function');
                    }
                } catch (error) {
                    console.error('Error accessing Alpine data:', error);
                }
            } else {
                console.error('Alpine element or Alpine not found');
            }
        }
        
        // Écouter quand Alpine est initialisé
        document.addEventListener('alpine:init', () => {
            // S'assurer que la fonction est disponible globalement
            window.openListingModal = function(listing) {
                const alpineElement = document.querySelector('[x-data]');
                if (alpineElement && window.Alpine) {
                    try {
                        const alpineData = window.Alpine.$data(alpineElement);
                        if (alpineData && typeof alpineData.openListing === 'function') {
                            alpineData.openListing(listing);
                        } else {
                            console.error('openListing method not found or is not a function');
                        }
                    } catch (error) {
                        console.error('Error accessing Alpine data:', error);
                    }
                } else {
                    console.error('Alpine element or Alpine not found');
                }
            };
        });
        
        // S'assurer que la fonction est disponible même si Alpine est déjà chargé
        if (typeof window.Alpine !== 'undefined') {
            window.openListingModal = function(listing) {
                const alpineElement = document.querySelector('[x-data]');
                if (alpineElement && window.Alpine) {
                    try {
                        const alpineData = window.Alpine.$data(alpineElement);
                        if (alpineData && typeof alpineData.openListing === 'function') {
                            alpineData.openListing(listing);
                        } else {
                            console.error('openListing method not found or is not a function');
                        }
                    } catch (error) {
                        console.error('Error accessing Alpine data:', error);
                    }
                } else {
                    console.error('Alpine element or Alpine not found');
                }
            };
        }
    </script>
    </body>
</html>
