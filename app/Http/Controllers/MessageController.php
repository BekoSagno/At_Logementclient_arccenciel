<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'listing_id' => 'nullable|exists:listings,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $message = Message::create([
            'user_id' => auth()->id(), // Lier automatiquement si connecté
            'listing_id' => $request->input('listing_id'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
        ]);

        // Créer une notification admin pour le nouveau message
        try {
            AdminNotificationService::notifyNewMessage($message);
        } catch (\Exception $e) {
            \Log::warning('Erreur lors de la création de la notification admin: ' . $e->getMessage());
        }

        // Envoyer une notification par email à l'admin
        try {
            // Récupérer le premier utilisateur admin (ou tous les admins)
            $adminEmail = env('ADMIN_EMAIL');
            
            if ($adminEmail) {
                // Si un email admin est configuré dans .env
                $admin = User::where('email', $adminEmail)->first();
                if ($admin) {
                    $admin->notify(new NewMessageNotification($message));
                }
            } else {
                // Sinon, envoyer au premier utilisateur (généralement l'admin)
                $admin = User::first();
                if ($admin) {
                    $admin->notify(new NewMessageNotification($message));
                }
            }
        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas bloquer la création du message
            \Log::warning('Erreur lors de l\'envoi de la notification email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => auth()->check() 
                ? 'Votre message a été envoyé. Vous pouvez le suivre dans votre espace.'
                : 'Votre message a été envoyé avec succès. Créez un compte pour suivre vos demandes.'
        ], 201);
    }
}
