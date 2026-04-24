<?php

namespace App\Services;

use App\Models\AdminNotification;
use App\Filament\Resources\MessageResource;
use App\Filament\Resources\ListingResource;
use Illuminate\Database\Eloquent\Model;

class AdminNotificationService
{
    /**
     * Créer une notification pour un nouveau message
     */
    public static function notifyNewMessage(Model $message): AdminNotification
    {
        // Charger la relation listing si elle existe
        if ($message->relationLoaded('listing') === false && method_exists($message, 'listing')) {
            $message->load('listing');
        }
        
        $listingTitle = $message->listing ? $message->listing->title : 'Demande générale';
        
        return AdminNotification::create([
            'type' => 'new_message',
            'title' => 'Nouveau message reçu',
            'message' => "{$message->name} a envoyé un message concernant : {$listingTitle}",
            'icon' => 'heroicon-o-envelope',
            'color' => 'primary',
            'notifiable_type' => get_class($message),
            'notifiable_id' => $message->id,
            'action_url' => MessageResource::getUrl('view', ['record' => $message->id]),
            'read' => false,
        ]);
    }
    
    /**
     * Créer une notification pour une nouvelle annonce
     */
    public static function notifyNewListing(Model $listing): AdminNotification
    {
        return AdminNotification::create([
            'type' => 'new_listing',
            'title' => 'Nouvelle annonce créée',
            'message' => "Une nouvelle annonce a été créée : {$listing->title}",
            'icon' => 'heroicon-o-home',
            'color' => 'success',
            'notifiable_type' => get_class($listing),
            'notifiable_id' => $listing->id,
            'action_url' => ListingResource::getUrl('edit', ['record' => $listing->id]),
            'read' => false,
        ]);
    }
    
    /**
     * Créer une notification pour une réponse de message
     */
    public static function notifyMessageResponse(Model $message): AdminNotification
    {
        return AdminNotification::create([
            'type' => 'message_response',
            'title' => 'Réponse envoyée',
            'message' => "Une réponse a été envoyée à {$message->name}",
            'icon' => 'heroicon-o-paper-airplane',
            'color' => 'success',
            'notifiable_type' => get_class($message),
            'notifiable_id' => $message->id,
            'action_url' => MessageResource::getUrl('view', ['record' => $message->id]),
            'read' => false,
        ]);
    }
    
    /**
     * Créer une notification pour une annonce publiée avec succès (programmée)
     */
    public static function notifyListingPublished(Model $listing): AdminNotification
    {
        return AdminNotification::create([
            'type' => 'listing_published',
            'title' => 'Annonce publiée avec succès',
            'message' => "L'annonce '{$listing->title}' a été publiée automatiquement selon la programmation.",
            'icon' => 'heroicon-o-check-circle',
            'color' => 'success',
            'notifiable_type' => get_class($listing),
            'notifiable_id' => $listing->id,
            'action_url' => ListingResource::getUrl('edit', ['record' => $listing->id]),
            'read' => false,
        ]);
    }
}
