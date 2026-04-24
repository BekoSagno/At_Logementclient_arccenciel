<?php

namespace App\Listeners;

use App\Events\ListingCreated;
use App\Models\User;
use App\Services\NotificationService;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class SendListingNotification // implements ShouldQueue
{
    // use InteractsWithQueue;

    protected NotificationService $notificationService;

    /**
     * Create the event listener.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(ListingCreated $event): void
    {
        // Notifier TOUS les utilisateurs pour les nouvelles annonces
        // Les utilisateurs peuvent désactiver les notifications dans leurs préférences
        $users = User::all();

        foreach ($users as $user) {
            // Notifier avec email forcé si les notifications système sont désactivées mais email activé
            $forceEmail = !$user->system_notifications_enabled && $user->email_notifications_enabled;
            
            $this->notificationService->createNotification(
                $user,
                'new_listing',
                'Nouvelle annonce disponible',
                "Une nouvelle annonce a été publiée : {$event->listing->title}",
                [
                    'listing_id' => $event->listing->id,
                    'listing_slug' => $event->listing->slug,
                ],
                $forceEmail
            );
        }
    }
}
