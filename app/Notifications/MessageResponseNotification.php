<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageResponseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Message $message,
        public string $response
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
        $listingTitle = $this->message->listing ? $this->message->listing->title : 'votre demande';
        $dashboardUrl = url('/dashboard');

        return (new MailMessage)
                    ->subject('Réponse à votre message - AT Logement')
                    ->greeting('Bonjour ' . $this->message->name . ',')
                    ->line('Nous avons bien reçu votre message concernant : **' . $listingTitle . '**')
                    ->line('**Notre réponse :**')
                    ->line($this->response)
                    ->when($this->message->listing, function ($mail) {
                        return $mail->action('Voir l\'annonce', route('listings.show', $this->message->listing->slug));
                    })
                    ->line('Vous pouvez suivre toutes vos demandes dans votre espace personnel.')
                    ->action('Accéder à mon espace', $dashboardUrl)
                    ->line('Merci de votre confiance !')
                    ->salutation('Cordialement,<br>L\'équipe AT Logement');
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
            'response' => $this->response,
        ];
    }
}
