<?php

namespace App\Services;

use App\Models\Listing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MakeWebhookService
{
    /**
     * Envoyer une annonce à Make.com pour diffusion sur les réseaux sociaux
     */
    public static function sendListingToMake(Listing $listing, string $event = 'publish'): bool
    {
        // Vérifier si le webhook est activé
        if (!config('services.make.enabled', false)) {
            Log::info('Make.com webhook est désactivé');
            return false;
        }

        $webhookUrl = config('services.make.webhook_url');
        
        if (empty($webhookUrl)) {
            Log::warning('URL du webhook Make.com non configurée');
            return false;
        }

        try {
            // Préparer les données pour Make.com
            $data = self::prepareListingData($listing, $event);
            
            // Envoyer les données à Make.com
            $response = Http::timeout(30)
                ->retry(2, 1000) // 2 tentatives avec 1 seconde d'attente
                ->post($webhookUrl, $data);
            
            if ($response->successful()) {
                Log::info('Annonce envoyée avec succès à Make.com', [
                    'listing_id' => $listing->id,
                    'title' => $listing->title,
                    'event' => $event
                ]);
                return true;
            } else {
                Log::warning('Erreur lors de l\'envoi à Make.com', [
                    'listing_id' => $listing->id,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception lors de l\'envoi à Make.com', [
                'listing_id' => $listing->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Préparer les données de l'annonce pour Make.com
     */
    public static function prepareListingData(Listing $listing, string $event): array
    {
        // Séparer les images et vidéos
        $images = [];
        $videos = [];
        
        if ($listing->images && is_array($listing->images)) {
            foreach ($listing->images as $mediaPath) {
                $fullUrl = self::getMediaUrl($mediaPath);
                if ($fullUrl) {
                    // Détecter si c'est une vidéo ou une image
                    $mimeType = self::getMediaMimeType($mediaPath);
                    if (str_starts_with($mimeType, 'video/')) {
                        $videos[] = $fullUrl;
                    } else {
                        $images[] = $fullUrl;
                    }
                }
            }
        }

        // Préparer les caractéristiques selon le type
        $characteristics = [];
        if ($listing->type === 'residential') {
            if ($listing->bedrooms) $characteristics['bedrooms'] = $listing->bedrooms;
            if ($listing->bathrooms) $characteristics['bathrooms'] = $listing->bathrooms;
            if ($listing->surface) $characteristics['surface'] = $listing->surface . ' m²';
        } elseif ($listing->type === 'land') {
            if ($listing->surface) $characteristics['surface'] = $listing->surface . ' m²';
            if ($listing->document_type) $characteristics['document_type'] = $listing->document_type;
        } elseif ($listing->type === 'commercial') {
            if ($listing->surface) $characteristics['surface'] = $listing->surface . ' m²';
        }

        // Préparer les hashtags suggérés
        $hashtags = self::generateHashtags($listing);

        // Construire l'URL complète de l'annonce
        $listingUrl = route('listings.show', ['listing' => $listing->slug]);

        // Données à envoyer à Make.com
        return [
            'event' => $event, // 'publish' ou 'update'
            'listing' => [
                'id' => $listing->id,
                'title' => $listing->title,
                'slug' => $listing->slug,
                'description' => $listing->description,
                'type' => $listing->type,
                'type_label' => self::getTypeLabel($listing->type),
                'service_status' => $listing->service_status,
                'price' => $listing->price ? (float) $listing->price : null,
                'currency' => $listing->currency ?? 'GNF',
                'price_formatted' => $listing->price ? number_format($listing->price, 0, ',', ' ') . ' ' . ($listing->currency ?? 'GNF') : null,
                'address' => $listing->address,
                'city' => $listing->city,
                'location' => trim(($listing->address ?? '') . ($listing->address && $listing->city ? ', ' : '') . ($listing->city ?? '')),
                'characteristics' => $characteristics,
                'custom_fields' => $listing->custom_fields ?? [],
                'images' => $images,
                'videos' => $videos,
                'thumbnail' => !empty($images) ? $images[0] : null,
                'url' => $listingUrl,
                'hashtags' => $hashtags,
                'published_at' => $listing->published_at?->toIso8601String(),
                'is_featured' => $listing->is_featured,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Obtenir l'URL complète d'un média
     */
    protected static function getMediaUrl(string $mediaPath): ?string
    {
        if (empty($mediaPath)) {
            return null;
        }

        // Si c'est déjà une URL complète
        if (str_starts_with($mediaPath, 'http://') || str_starts_with($mediaPath, 'https://')) {
            return $mediaPath;
        }

        // Construire l'URL complète
        $filePath = str_starts_with($mediaPath, 'listings/') ? $mediaPath : 'listings/' . $mediaPath;
        
        // Utiliser Storage::url() pour obtenir l'URL publique
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->url($filePath);
        }
        
        // Fallback : utiliser la route
        return route('listing.image', ['path' => str_replace('listings/', '', $filePath)]);
    }

    /**
     * Obtenir le type MIME d'un média
     */
    protected static function getMediaMimeType(string $mediaPath): string
    {
        $filePath = str_starts_with($mediaPath, 'listings/') ? $mediaPath : 'listings/' . $mediaPath;
        
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->mimeType($filePath) ?? 'application/octet-stream';
        }
        
        // Deviner depuis l'extension
        $extension = strtolower(pathinfo($mediaPath, PATHINFO_EXTENSION));
        return match($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'mp4' => 'video/mp4',
            'mov' => 'video/quicktime',
            'avi' => 'video/x-msvideo',
            'webm' => 'video/webm',
            default => 'application/octet-stream',
        };
    }

    /**
     * Générer des hashtags suggérés selon le type et la localisation
     */
    protected static function generateHashtags(Listing $listing): array
    {
        $hashtags = [];
        
        // Hashtags selon le type
        $typeHashtags = [
            'residential' => ['Immobilier', 'Maison', 'Appartement', 'Location', 'Vente'],
            'commercial' => ['Immobilier', 'Commercial', 'Bureau', 'Local'],
            'land' => ['Immobilier', 'Terrain', 'Foncier'],
            'service' => ['Service', 'Immobilier', 'Conseil'],
        ];
        
        if (isset($typeHashtags[$listing->type])) {
            $hashtags = array_merge($hashtags, $typeHashtags[$listing->type]);
        }
        
        // Hashtags selon la ville
        if ($listing->city) {
            $hashtags[] = $listing->city;
            $hashtags[] = 'Conakry'; // Toujours ajouter Conakry si c'est la capitale
        }
        
        // Hashtags généraux
        $hashtags = array_merge($hashtags, ['ATLogement', 'Guinée']);
        
        // Convertir en hashtags avec #
        return array_map(function($tag) {
            return '#' . preg_replace('/[^a-zA-Z0-9]/', '', $tag);
        }, array_unique($hashtags));
    }

    /**
     * Obtenir le label du type en français
     */
    protected static function getTypeLabel(string $type): string
    {
        return match($type) {
            'residential' => 'Résidentiel',
            'commercial' => 'Commercial',
            'land' => 'Terrain',
            'service' => 'Service',
            default => $type,
        };
    }
}
