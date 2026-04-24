<?php

namespace App\Services;

use App\Mail\NotificationMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Créer une notification pour un utilisateur
     */
    public function createNotification(User $user, string $type, string $title, string $message, array $data = [], bool $forceEmail = false): ?Notification
    {
        $notification = null;
        
        // Créer la notification système si activée
        if ($user->system_notifications_enabled) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'read' => false,
            ]);
        } elseif ($forceEmail) {
            // Créer une notification temporaire pour l'email même si système désactivé
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'read' => true, // Marquer comme lu car pas affichée dans le système
            ]);
        }

        // Envoyer l'email si activé (même si notification système désactivée)
        if ($user->email_notifications_enabled && $notification) {
            $this->sendEmailNotification($user, $notification);
        }

        return $notification;
    }

    /**
     * Notifier tous les utilisateurs
     */
    public function notifyAllUsers(string $type, string $title, string $message, array $data = []): void
    {
        $users = User::where('system_notifications_enabled', true)->get();

        foreach ($users as $user) {
            $this->createNotification($user, $type, $title, $message, $data);
        }
    }

    /**
     * Envoyer une notification par email
     */
    protected function sendEmailNotification(User $user, Notification $notification): void
    {
        try {
            Mail::to($user->email)->send(new NotificationMail($notification));
            $notification->markEmailAsSent();
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de notification: ' . $e->getMessage());
        }
    }

    /**
     * Notifier pour une nouvelle annonce
     */
    public function notifyNewListing(User $user, $listing): void
    {
        $this->createNotification(
            $user,
            'new_listing',
            'Nouvelle annonce disponible',
            "Une nouvelle annonce a été publiée : {$listing->title}",
            [
                'listing_id' => $listing->id,
                'listing_slug' => $listing->slug,
            ]
        );
    }

    /**
     * Notifier pour un message de l'admin
     */
    public function notifyAdminMessage(User $user, string $message): void
    {
        $this->createNotification(
            $user,
            'admin_message',
            'Message de l\'administrateur',
            $message,
            []
        );
    }

    /**
     * Notifier pour une réponse à un message
     */
    public function notifyMessageResponse(User $user, $message): void
    {
        $listingTitle = $message->listing ? $message->listing->title : 'Votre demande';
        
        $this->createNotification(
            $user,
            'message_response',
            'Réponse à votre message',
            "Vous avez reçu une réponse concernant : {$listingTitle}",
            [
                'message_id' => $message->id,
                'listing_id' => $message->listing_id,
            ]
        );
    }
}
