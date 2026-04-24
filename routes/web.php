<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Models\Listing;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    // Récupérer les annonces publiées ET les annonces non disponibles (pour afficher le message)
    $listings = Listing::where('status', true)
        ->where(function ($q) {
            $q->where(function ($subQ) {
                $subQ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
            })->orWhere(function ($subQ) {
                $subQ->whereNotNull('scheduled_at')
                     ->where('scheduled_at', '<=', now())
                     ->whereNull('published_at');
            });
        })
        ->orderBy('is_featured', 'desc')
        ->orderBy('updated_at', 'desc')
        ->orderBy('published_at', 'desc')
        ->take(9)
        ->get();
    
    // Récupérer les données de l'utilisateur connecté pour pré-remplir le formulaire
    $userData = null;
    if (auth()->check()) {
        $user = auth()->user();
        // Récupérer le dernier téléphone utilisé par l'utilisateur
        $lastMessage = $user->messages()->latest()->first();
        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $lastMessage ? $lastMessage->phone : '',
        ];
    }
    
    return view('welcome', compact('listings', 'userData'));
})->name('home');

Route::get('/listings/search', [ListingController::class, 'search'])->name('listings.search');

Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

Route::get('/listings/{listing:slug}', function (Listing $listing) {
    // Récupérer les données de l'utilisateur connecté pour pré-remplir le formulaire
    $userData = null;
    if (auth()->check()) {
        $user = auth()->user();
        // Récupérer le dernier téléphone utilisé par l'utilisateur
        $lastMessage = $user->messages()->latest()->first();
        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $lastMessage ? $lastMessage->phone : '',
        ];
    }
    return view('listings.show', compact('listing', 'userData'));
})->name('listings.show');

// Route pour servir les images des listings depuis le disque public
Route::get('/storage/listings/{path}', function (string $path) {
    // Les images sont stockées dans storage/app/public/listings/
    // Le path peut être "listings/image.jpg" ou juste "image.jpg"
    $filePath = str_starts_with($path, 'listings/') ? $path : 'listings/' . $path;
    
    // Essayer d'abord le disque public (où Filament stocke les fichiers)
    if (Storage::disk('public')->exists($filePath)) {
        $file = Storage::disk('public')->get($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Access-Control-Allow-Origin', '*'); // Permettre CORS
    }
    
    // Sinon essayer le disque local
    if (Storage::disk('local')->exists($filePath)) {
        $file = Storage::disk('local')->get($filePath);
        $mimeType = Storage::disk('local')->mimeType($filePath);
        
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Access-Control-Allow-Origin', '*'); // Permettre CORS
    }
    
    abort(404);
})->where('path', '.*')->name('listing.image');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/favorites', [UserDashboardController::class, 'favorites'])->name('dashboard.favorites');
    
    // Routes pour le profil
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [\App\Http\Controllers\UserProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    
    // Routes pour les notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/api/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('api.notifications.unread');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // Route pour vérifier les nouvelles réponses aux messages
    Route::get('/api/messages/check-responses', [UserDashboardController::class, 'checkNewResponses'])->name('api.messages.check-responses');
    
    // Routes pour les favoris
    Route::post('/listings/{listing}/favorite', [\App\Http\Controllers\FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/listings/{listing}/favorite', [\App\Http\Controllers\FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/listings/{listing}/favorite/check', [\App\Http\Controllers\FavoriteController::class, 'check'])->name('favorites.check');
    
    // Route API pour récupérer les données de l'utilisateur connecté (sécurisée)
    Route::get('/api/user-data', function () {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }
        
        // Récupérer le dernier téléphone utilisé par l'utilisateur
        $lastMessage = $user->messages()->latest()->first();
        
        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $lastMessage ? $lastMessage->phone : '',
            ]
        ]);
    })->name('api.user-data');
});

// Route de prévisualisation admin (accessible uniquement aux admins)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/listings/{listing}/preview', function (Listing $listing) {
        // Vérifier que l'utilisateur est authentifié
        if (!auth()->check()) {
            abort(403, 'Accès non autorisé');
        }
        
        // Récupérer les données de l'utilisateur pour pré-remplir le formulaire
        $user = auth()->user();
        $lastMessage = $user->messages()->latest()->first();
        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $lastMessage ? $lastMessage->phone : '',
        ];
        
        return view('listings.preview', compact('listing', 'userData'));
    })->name('admin.listings.preview');
    
    // Route API pour récupérer le nombre de notifications admin non lues
    Route::get('/admin/api/notifications/unread-count', function () {
        if (!auth()->check()) {
            return response()->json(['count' => 0], 401);
        }
        
        $count = \App\Models\AdminNotification::unread()->count();
        return response()->json(['count' => $count]);
    })->name('admin.api.notifications.unread-count');
});

require __DIR__.'/auth.php';
