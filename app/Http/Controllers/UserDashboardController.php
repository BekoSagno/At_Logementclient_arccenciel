<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Messages de l'utilisateur
        $messages = $user->messages()
            ->with('listing')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Annonces avec lesquelles l'utilisateur a interagi
        $interactedListings = Listing::whereHas('messages', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['messages' => function ($query) use ($user) {
            $query->where('user_id', $user->id)->orderBy('created_at', 'desc');
        }])
        ->distinct()
        ->get();
        
        // Statistiques
        $stats = [
            'total_messages' => $messages->count(),
            'read_responses' => $messages->whereNotNull('read_at')->count(), // Messages lus par l'admin = réponses
            'active_requests' => $interactedListings->count(),
            'total_favorites' => $user->favorites()->count(),
        ];
        
        return view('user.dashboard', compact('messages', 'interactedListings', 'stats'));
    }

    /**
     * Afficher la page Mes favoris
     */
    public function favorites()
    {
        $user = Auth::user();
        
        $favorites = $user->favorites()
            ->with('listing')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('user.favorites', compact('favorites'));
    }

    /**
     * Vérifier s'il y a de nouvelles réponses aux messages
     */
    public function checkNewResponses()
    {
        $user = Auth::user();
        
        // Vérifier s'il y a des messages avec des réponses récentes (dans les 30 dernières secondes)
        $recentResponses = Message::where('user_id', $user->id)
            ->whereNotNull('admin_response')
            ->where('response_sent_at', '>=', now()->subSeconds(30))
            ->exists();
        
        return response()->json([
            'hasNewResponses' => $recentResponses
        ]);
    }
}
