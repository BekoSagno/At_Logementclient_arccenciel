<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Message $message
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $listingTitle = $this->message->listing ? $this->message->listing->title : 'Message général';
        
        // URL vers la page de visualisation du message dans Filament
        $adminUrl = \App\Filament\Resources\MessageResource::getUrl('view', ['record' => $this->message->id]);

        return (new MailMessage)
                    ->subject('Nouveau message reçu - AT Logement')
                    ->greeting('Bonjour,')
                    ->line('Vous avez reçu un nouveau message sur votre plateforme AT Logement.')
                    ->line('**De :** ' . $this->message->name)
                    ->line('**Email :** ' . $this->message->email)
                    ->line('**Téléphone :** ' . $this->message->phone)
                    ->line('**Annonce concernée :** ' . $listingTitle)
                    ->when($this->message->message, function ($mail) {
                        return $mail->line('**Message :**')
                                    ->line($this->message->message);
                    })
                    ->action('Voir le message', $adminUrl)
                    ->line('Merci d\'utiliser AT Logement !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message_id' => $this->message->id,
            'name' => $this->message->name,
            'email' => $this->message->email,
        ];
    }
}
