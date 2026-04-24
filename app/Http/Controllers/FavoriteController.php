<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Ajouter une annonce aux favoris
     */
    public function store(Request $request, Listing $listing)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour ajouter aux favoris'
            ], 401);
        }

        // Vérifier si déjà en favoris
        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('listing_id', $listing->id)
            ->first();

        if ($existingFavorite) {
            return response()->json([
                'success' => false,
                'message' => 'Cette annonce est déjà dans vos favoris',
                'is_favorite' => true
            ], 400);
        }

        // Créer le favori
        Favorite::create([
            'user_id' => $user->id,
            'listing_id' => $listing->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Annonce ajoutée aux favoris',
            'is_favorite' => true
        ]);
    }

    /**
     * Retirer une annonce des favoris
     */
    public function destroy(Listing $listing)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté'
            ], 401);
        }

        $favorite = Favorite::where('user_id', $user->id)
            ->where('listing_id', $listing->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'message' => 'Annonce retirée des favoris',
                'is_favorite' => false
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Cette annonce n\'est pas dans vos favoris',
            'is_favorite' => false
        ], 400);
    }

    /**
     * Vérifier si une annonce est en favoris
     */
    public function check(Listing $listing)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'is_favorite' => false
            ]);
        }

        $isFavorite = Favorite::where('user_id', $user->id)
            ->where('listing_id', $listing->id)
            ->exists();

        return response()->json([
            'is_favorite' => $isFavorite
        ]);
    }
}
