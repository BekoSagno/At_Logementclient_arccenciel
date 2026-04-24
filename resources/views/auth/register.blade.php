<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription - AT Logement</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
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
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .gradient-animated {
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
        }
        
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-orange-50/30 to-gray-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4 py-6" x-data="{ 
        showPassword: false, 
        showPasswordConfirmation: false,
        isLoading: false,
        password: '',
        passwordStrength: 0,
        checkPasswordStrength() {
            let strength = 0;
            if (this.password.length >= 8) strength += 25;
            if (this.password.match(/[a-z]/) && this.password.match(/[A-Z]/)) strength += 25;
            if (this.password.match(/\d/)) strength += 25;
            if (this.password.match(/[^a-zA-Z\d]/)) strength += 25;
            this.passwordStrength = strength;
        }
    }">
        <!-- Container principal - Formulaire compact centré -->
        <div class="w-full max-w-md animate-slide-in-right">
            <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 transform hover:shadow-3xl transition-shadow duration-500 relative">
                <!-- Flèche de retour en haut -->
                <a href="{{ route('home') }}" class="absolute top-4 left-4 w-10 h-10 flex items-center justify-center text-gray-600 hover:text-[#86c14f] hover:bg-gray-100 rounded-lg transition-all duration-300 transform hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                
                <!-- Header du formulaire -->
                <div class="text-center mb-5">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Créer un compte</h2>
                    <p class="text-sm text-gray-600">Rejoignez-nous dès aujourd'hui</p>
                </div>

                    <!-- Messages d'erreur -->
                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg animate-fade-in-up">
                            <ul class="text-xs text-red-800 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Formulaire -->
                    <form method="POST" action="{{ route('register') }}" @submit="isLoading = true">
                        @csrf
                        
                        <!-- Nom -->
                        <div class="mb-4 animate-fade-in-up" style="animation-delay: 0.1s">
                            <label for="name" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Nom complet
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border-2 border-gray-200 rounded-lg focus:border-[#86c14f] focus:ring-2 focus:ring-[#86c14f]/20 outline-none transition-all duration-300 bg-gray-50 focus:bg-white"
                                    placeholder="Votre nom complet"
                                >
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-4 animate-fade-in-up" style="animation-delay: 0.2s">
                            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Adresse email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="username"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border-2 border-gray-200 rounded-lg focus:border-[#86c14f] focus:ring-2 focus:ring-[#86c14f]/20 outline-none transition-all duration-300 bg-gray-50 focus:bg-white"
                                    placeholder="votre@email.com"
                                >
                            </div>
                        </div>
                        
                        <!-- Mot de passe -->
                        <div class="mb-4 animate-fade-in-up" style="animation-delay: 0.3s">
                            <label for="password" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Mot de passe
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    x-model="password"
                                    @input="checkPasswordStrength()"
                                    class="w-full pl-10 pr-10 py-2.5 text-sm border-2 border-gray-200 rounded-lg focus:border-[#86c14f] focus:ring-2 focus:ring-[#86c14f]/20 outline-none transition-all duration-300 bg-gray-50 focus:bg-white"
                                    placeholder="••••••••"
                                >
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#86c14f] transition-colors"
                                >
                                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            <!-- Indicateur de force du mot de passe -->
                            <div x-show="password.length > 0" class="mt-1.5 flex gap-1">
                                <div class="flex-1 password-strength h-1.5" :class="passwordStrength >= 25 ? 'bg-red-400' : 'bg-gray-200'"></div>
                                <div class="flex-1 password-strength h-1.5" :class="passwordStrength >= 50 ? 'bg-orange-400' : 'bg-gray-200'"></div>
                                <div class="flex-1 password-strength h-1.5" :class="passwordStrength >= 75 ? 'bg-yellow-400' : 'bg-gray-200'"></div>
                                <div class="flex-1 password-strength h-1.5" :class="passwordStrength >= 100 ? 'bg-[#86c14f]' : 'bg-gray-200'"></div>
                            </div>
                        </div>
                        
                        <!-- Confirmation mot de passe -->
                        <div class="mb-5 animate-fade-in-up" style="animation-delay: 0.4s">
                            <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Confirmer le mot de passe
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="password_confirmation"
                                    :type="showPasswordConfirmation ? 'text' : 'password'"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    class="w-full pl-10 pr-10 py-2.5 text-sm border-2 border-gray-200 rounded-lg focus:border-[#86c14f] focus:ring-2 focus:ring-[#86c14f]/20 outline-none transition-all duration-300 bg-gray-50 focus:bg-white"
                                    placeholder="••••••••"
                                >
                                <button
                                    type="button"
                                    @click="showPasswordConfirmation = !showPasswordConfirmation"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#86c14f] transition-colors"
                                >
                                    <svg x-show="!showPasswordConfirmation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPasswordConfirmation" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Bouton d'inscription -->
                        <button
                            type="submit"
                            :disabled="isLoading"
                            :class="isLoading ? 'opacity-75 cursor-not-allowed' : 'hover:shadow-xl hover:scale-[1.02] active:scale-[0.98]'"
                            class="w-full py-3 bg-gradient-to-r from-[#86c14f] to-[#87c04f] text-white rounded-lg font-bold text-base shadow-lg transform transition-all duration-300 flex items-center justify-center gap-2 mb-4 animate-fade-in-up"
                            style="animation-delay: 0.5s"
                        >
                            <span x-show="!isLoading">Créer mon compte</span>
                            <span x-show="isLoading" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Création...
                            </span>
                        </button>
                        
                        <!-- Lien vers connexion -->
                        <div class="text-center animate-fade-in-up" style="animation-delay: 0.6s">
                            <p class="text-xs text-gray-600 mb-1">
                                Vous avez déjà un compte ?
                            </p>
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center gap-1.5 text-sm font-bold text-[#86c14f] hover:text-[#87c04f] transition-colors duration-300 group"
                            >
                                <span>Se connecter</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
