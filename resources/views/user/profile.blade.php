<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mon Compte - AT Logement</title>
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
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 via-orange-50/20 to-gray-50 min-h-screen">
    <!-- Header -->
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
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all duration-300">
                        Mon Espace
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
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 animate-fade-in-up">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-[#f3a43e] to-[#f97316] rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                Mon Compte
            </h1>
            <p class="text-gray-600">Gérez vos informations personnelles et préférences</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-[#86c14f] rounded-lg animate-fade-in-up">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Photo de profil -->
            <div class="bg-white rounded-2xl shadow-xl p-6 animate-fade-in-up">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Photo de profil</h2>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-4 border-[#f3a43e] shadow-lg">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#f3a43e] to-[#f97316] flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                        <label for="avatar" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300 cursor-pointer">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Changer la photo
                        </label>
                        @if($user->avatar)
                            <form method="POST" action="{{ route('profile.avatar.delete') }}" class="inline ml-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">Supprimer</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informations personnelles -->
            <div class="bg-white rounded-2xl shadow-xl p-6 animate-fade-in-up" style="animation-delay: 0.1s">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Informations personnelles</h2>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom complet</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-[#f3a43e] focus:ring-2 focus:ring-[#f3a43e]/20 outline-none transition-all duration-300">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-[#f3a43e] focus:ring-2 focus:ring-[#f3a43e]/20 outline-none transition-all duration-300">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-[#f3a43e] focus:ring-2 focus:ring-[#f3a43e]/20 outline-none transition-all duration-300">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Préférences de notifications -->
            <div class="bg-white rounded-2xl shadow-xl p-6 animate-fade-in-up" style="animation-delay: 0.2s">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Préférences de notifications</h2>
                
                <div class="space-y-4">
                    <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="email_notifications_enabled" value="1" {{ old('email_notifications_enabled', $user->email_notifications_enabled) ? 'checked' : '' }}
                               class="w-5 h-5 text-[#f3a43e] border-gray-300 rounded focus:ring-[#f3a43e] focus:ring-2">
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">Notifications par email</div>
                            <div class="text-sm text-gray-600">Recevez les notifications par email dans votre boîte de réception</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">
                        <input type="checkbox" name="system_notifications_enabled" value="1" {{ old('system_notifications_enabled', $user->system_notifications_enabled) ? 'checked' : '' }}
                               class="w-5 h-5 text-[#f3a43e] border-gray-300 rounded focus:ring-[#f3a43e] focus:ring-2">
                        <div class="flex-1">
                            <div class="font-semibold text-gray-900">Notifications système</div>
                            <div class="text-sm text-gray-600">Recevez les notifications dans votre espace personnel</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Bouton de sauvegarde -->
            <div class="flex justify-end gap-4 animate-fade-in-up" style="animation-delay: 0.3s">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg font-bold hover:bg-gray-600 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                    Annuler
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-[#f3a43e] to-[#f97316] text-white rounded-lg font-bold hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </main>
</body>
</html>
