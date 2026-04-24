<?php

namespace App\Observers;

use App\Models\Message;
use App\Services\AdminNotificationService;
use Illuminate\Support\Facades\Log;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        // Créer une notification pour l'admin lorsqu'un nouveau message est reçu
        try {
            AdminNotificationService::notifyNewMessage($message);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la notification admin: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        // Si une réponse a été ajoutée, créer une notification
        if ($message->wasChanged('admin_response') && $message->admin_response !== null) {
            try {
                AdminNotificationService::notifyMessageResponse($message);
            } catch (\Exception $e) {
                Log::error('Erreur lors de la création de la notification de réponse: ' . $e->getMessage());
            }
        }
    }
}
