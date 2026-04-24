<?php

namespace App\Filament\Resources\MessageResource\Pages;

use App\Filament\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Mail\MessageResponseMail;
use App\Services\NotificationService;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class ViewMessage extends ViewRecord
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('markAsRead')
                ->label('Marquer comme lu')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->read_at === null)
                ->action(function () {
                    $this->record->update(['read_at' => now()]);
                    $this->redirect(static::getUrl(['record' => $this->record]));
                })
                ->requiresConfirmation(),
            Actions\Action::make('sendResponse')
                ->label('Envoyer une réponse')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->form([
                    Textarea::make('response')
                        ->label('Votre réponse')
                        ->required()
                        ->rows(8)
                        ->placeholder('Tapez votre réponse au client ici...')
                        ->helperText('Cette réponse sera envoyée par email au client.'),
                ])
                ->modalHeading('Répondre au message')
                ->modalDescription('Votre réponse sera envoyée par email à ' . $this->record->email)
                ->modalSubmitActionLabel('Envoyer la réponse')
                ->action(function (array $data) {
                    $response = $data['response'];
                    
                    // Enregistrer la réponse dans la base de données
                    $this->record->update([
                        'admin_response' => $response,
                        'response_sent_at' => now(),
                        'read_at' => $this->record->read_at ?? now(), // Marquer comme lu si pas déjà lu
                    ]);
                    
                    // Envoyer l'email de réponse au client (synchrone, pas en queue)
                    try {
                        // Charger la relation listing si elle existe
                        $this->record->load('listing');
                        
                        // Forcer l'envoi synchrone
                        $mailable = new MessageResponseMail($this->record, $response);
                        
                        // Vérifier la configuration email avant l'envoi
                        $mailer = config('mail.default');
                        $mailHost = config('mail.mailers.smtp.host');
                        \Log::info("=== TENTATIVE D'ENVOI D'EMAIL ===");
                        \Log::info("Mailer: {$mailer}");
                        \Log::info("Host: {$mailHost}");
                        \Log::info("Email destinataire: {$this->record->email}");
                        \Log::info("Message ID: {$this->record->id}");
                        
                        // Envoyer l'email de manière synchrone avec gestion d'erreur explicite
                        $result = Mail::mailer($mailer)->to($this->record->email)->send($mailable);
                        
                        \Log::info('✅ Email de réponse envoyé avec succès à: ' . $this->record->email);
                        \Log::info("Résultat: " . ($result ? 'OK' : 'ÉCHEC'));
                        
                        // Afficher une notification de succès
                        Notification::make()
                            ->success()
                            ->title('✅ Email envoyé avec succès')
                            ->body('L\'email a été envoyé à ' . $this->record->email)
                            ->persistent()
                            ->send();
                            
                    } catch (\Swift_TransportException $e) {
                        // Erreur de transport SMTP
                        \Log::error('❌ Erreur SMTP: ' . $e->getMessage());
                        \Log::error('Code: ' . $e->getCode());
                        
                        Notification::make()
                            ->danger()
                            ->title('❌ Erreur SMTP')
                            ->body('Impossible de se connecter au serveur email. Vérifiez la configuration SMTP.')
                            ->persistent()
                            ->send();
                            
                    } catch (\Exception $e) {
                        // Autre erreur
                        \Log::error('❌ Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
                        \Log::error('Fichier: ' . $e->getFile() . ':' . $e->getLine());
                        \Log::error('Type: ' . get_class($e));
                        \Log::error('Stack trace: ' . $e->getTraceAsString());
                        
                        Notification::make()
                            ->danger()
                            ->title('❌ Erreur d\'envoi d\'email')
                            ->body('Erreur: ' . $e->getMessage())
                            ->persistent()
                            ->send();
                    }
                    
                    // Créer une notification admin pour la réponse envoyée
                    try {
                        \App\Services\AdminNotificationService::notifyMessageResponse($this->record);
                    } catch (\Exception $e) {
                        \Log::warning('Erreur lors de la création de la notification admin: ' . $e->getMessage());
                    }
                    
                    // Créer une notification dans le dashboard client si l'utilisateur est connecté
                    if ($this->record->user_id) {
                        $user = User::find($this->record->user_id);
                        if ($user) {
                            $notificationService = app(NotificationService::class);
                            $notificationService->notifyMessageResponse($user, $this->record);
                        }
                    }
                    
                    // Rediriger vers la page de visualisation
                    $this->redirect(static::getUrl(['record' => $this->record]));
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Réponse envoyée avec succès')
                        ->body('La réponse a été envoyée par email au client.')
                ),
        ];
    }
}


