<nav
    x-data="{
        open: false,
        lastScroll: 0,
        headerVisible: true,
        init() {
            this.lastScroll = window.pageYOffset || document.documentElement.scrollTop;
            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                if (currentScroll > this.lastScroll) {
                    // Défilement vers le bas (même 1mm) - cacher
                    this.headerVisible = false;
                } else if (currentScroll < this.lastScroll) {
                    // Défilement vers le haut (même 1mm) - afficher
                    this.headerVisible = true;
                }
                this.lastScroll = currentScroll;
            });
        }
    }"
    x-show="headerVisible"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="-translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="-translate-y-full"
    class="header-smooth bg-white lg:shadow-sm shadow-sm fixed top-0 z-50 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14 lg:h-20">
            <!-- Logo (Gauche) -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="lg:block">
                    <!-- Logo Desktop (sur fond blanc) -->
                    <img src="{{ asset('images/logo.jpg') }}" alt="AT Logement" class="hidden lg:block" style="width: 2cm; height: 2cm; object-fit: contain;">
                    <!-- Logo Mobile (sur fond blanc) -->
                    <img src="{{ asset('images/logo.jpg') }}" alt="AT Logement" class="lg:hidden h-12 w-auto" style="object-fit: contain;">
                </a>
            </div>

            <!-- Liens Centres (Desktop uniquement) - Conteneur avec bordure orange -->
            <div class="hidden lg:flex items-center">
                <div class="bg-white border-2 border-at-orange rounded-full px-8 py-3 flex items-center space-x-8 shadow-sm">
                    <a href="{{ route('home') }}" class="text-black font-bold hover:text-at-orange transition-colors duration-300">Accueil</a>
                    <a href="#annonces" class="text-black font-bold hover:text-at-orange transition-colors duration-300">Annonce</a>
                    <a href="{{ route('home') }}#services" class="text-black font-bold hover:text-at-orange transition-colors duration-300">Services</a>
                    <a href="#apropos" class="text-black font-bold hover:text-at-orange transition-colors duration-300">A Propos</a>
                </div>
            </div>

            <!-- Bouton Mon Espace (Desktop uniquement) -->
            <div class="hidden lg:flex items-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary bg-at-orange text-black px-6 py-3 rounded-full flex items-center gap-2 hover:bg-at-orange-600 hover:shadow-at-orange active:scale-95 transition-all duration-300 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Mon Espace</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary bg-at-orange text-black px-6 py-3 rounded-full flex items-center gap-2 hover:bg-at-orange-600 hover:shadow-at-orange active:scale-95 transition-all duration-300 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Mon Espace</span>
                    </a>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile uniquement) -->
            <button
                @click="open = !open"
                class="lg:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors"
                aria-label="Menu"
            >
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            @click.away="open = false"
            class="lg:hidden bg-white/95 backdrop-blur-md border-t border-white/20 shadow-lg"
            style="display: none;"
        >
            <div class="px-4 py-6 space-y-4">
                <a href="{{ route('home') }}" class="block py-2 text-gray-700 font-medium hover:text-at-orange transition-colors duration-300" @click="open = false">Accueil</a>
                <a href="#annonces" class="block py-2 text-gray-700 font-medium hover:text-at-orange transition-colors duration-300" @click="open = false">Annonce</a>
                <a href="{{ route('home') }}#services" class="block py-2 text-gray-700 font-medium hover:text-at-orange transition-colors duration-300" @click="open = false">Services</a>
                <a href="#apropos" class="block py-2 text-gray-700 font-medium hover:text-at-orange transition-colors duration-300" @click="open = false">A Propos</a>
                <div class="pt-4 border-t border-gray-200">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary flex items-center justify-center gap-2 bg-at-orange text-black hover:bg-at-orange-600 hover:shadow-at-orange active:scale-95 px-5 py-2 rounded-full font-bold transition-all duration-300" @click="open = false">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Mon Espace</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary flex items-center justify-center gap-2 bg-at-orange text-black hover:bg-at-orange-600 hover:shadow-at-orange active:scale-95 px-5 py-2 rounded-full font-bold transition-all duration-300" @click="open = false">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span>Mon Espace</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
