<?php

namespace App\Observers;

use App\Models\Listing;
use App\Services\MakeWebhookService;

class ListingObserver
{
    /**
     * Handle the Listing "created" event.
     */
    public function created(Listing $listing): void
    {
        // Si l'annonce est créée directement comme publiée
        if ($listing->status && $listing->published_at && $listing->published_at <= now()) {
            MakeWebhookService::sendListingToMake($listing);
        }
    }

    /**
     * Handle the Listing "updated" event.
     */
    public function updated(Listing $listing): void
    {
        // Vérifier si l'annonce vient d'être publiée
        $wasPublished = $listing->getOriginal('status') && 
                       $listing->getOriginal('published_at') && 
                       $listing->getOriginal('published_at') <= now();
        
        $isNowPublished = $listing->status && 
                         $listing->published_at && 
                         $listing->published_at <= now();
        
        // Si l'annonce vient d'être publiée (status passe à true OU published_at vient d'être défini)
        if (!$wasPublished && $isNowPublished) {
            MakeWebhookService::sendListingToMake($listing);
        }
        
        // Si l'annonce était déjà publiée et a été mise à jour (changements importants)
        // Optionnel : envoyer aussi les mises à jour
        // if ($wasPublished && $isNowPublished && $listing->wasChanged(['title', 'description', 'price', 'images'])) {
        //     MakeWebhookService::sendListingToMake($listing, 'update');
        // }
    }

    /**
     * Handle the Listing "deleted" event.
     */
    public function deleted(Listing $listing): void
    {
        //
    }

    /**
     * Handle the Listing "restored" event.
     */
    public function restored(Listing $listing): void
    {
        //
    }

    /**
     * Handle the Listing "force deleted" event.
     */
    public function forceDeleted(Listing $listing): void
    {
        //
    }
}
