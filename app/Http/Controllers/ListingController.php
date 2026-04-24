<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    /**
     * Recherche et filtrage des annonces
     */
    public function search(Request $request)
    {
        // Inclure les annonces publiées ET les annonces non disponibles (pour afficher le message)
        $query = Listing::where('status', true)
            ->where(function ($q) {
                $q->where(function ($subQ) {
                    $subQ->whereNotNull('published_at')
                         ->where('published_at', '<=', now());
                })->orWhere(function ($subQ) {
                    $subQ->whereNotNull('scheduled_at')
                         ->where('scheduled_at', '<=', now())
                         ->whereNull('published_at');
                });
            });

        // Recherche textuelle sur titre et description
        $query->when($request->filled('query'), function ($q) use ($request) {
            $searchTerm = $request->input('query');
            $q->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('title', 'like', "%{$searchTerm}%")
                         ->orWhere('description', 'like', "%{$searchTerm}%")
                         ->orWhere('address', 'like', "%{$searchTerm}%")
                         ->orWhere('city', 'like', "%{$searchTerm}%");
            });
        });

        // Filtre par localisation (ville)
        $query->when($request->filled('location'), function ($q) use ($request) {
            $q->where('city', 'like', "%{$request->input('location')}%");
        });

        // Filtre par type de bien
        $query->when($request->filled('type'), function ($q) use ($request) {
            $q->where('type', $request->input('type'));
        });

        // Filtre par transaction (si le champ existe dans la base de données)
        // Note: Si ce champ n'existe pas encore, vous devrez l'ajouter via une migration
        // Pour l'instant, on le commente
        // $query->when($request->filled('transaction'), function ($q) use ($request) {
        //     $q->where('transaction', $request->input('transaction'));
        // });

        // Filtre par nombre minimum de chambres
        $query->when($request->filled('bedrooms'), function ($q) use ($request) {
            $q->where('bedrooms', '>=', (int) $request->input('bedrooms'));
        });

        // Filtre par budget minimum
        $query->when($request->filled('budget_min'), function ($q) use ($request) {
            $q->where('price', '>=', (float) $request->input('budget_min'));
        });

        // Filtre par budget maximum
        $query->when($request->filled('budget_max'), function ($q) use ($request) {
            $q->where('price', '<=', (float) $request->input('budget_max'));
        });

        // Tri : mises en avant en premier (par updated_at), puis par date de publication
        $listings = $query->orderBy('is_featured', 'desc')
                         ->orderBy('updated_at', 'desc')
                         ->latest('published_at')
                         ->paginate(12)
                         ->withQueryString();

        return view('listings.index', compact('listings'));
    }
}

