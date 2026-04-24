<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer quelques listings pour les messages
        $listings = Listing::where('status', true)->take(2)->get();

        if ($listings->count() > 0) {
            // Message lu pour la première annonce
            Message::create([
                'listing_id' => $listings->first()->id,
                'name' => 'Mamadou Diallo',
                'email' => 'mamadou.diallo@example.com',
                'phone' => '+224 612 34 56 78',
                'message' => 'Bonjour, je suis intéressé par votre annonce de location. Pourriez-vous me donner plus de détails sur la disponibilité et les conditions ?',
                'read_at' => now()->subHours(2),
            ]);

            // Message non lu pour la première annonce
            Message::create([
                'listing_id' => $listings->first()->id,
                'name' => 'Fatoumata Camara',
                'email' => 'fatoumata.camara@example.com',
                'phone' => '+224 655 12 34 56',
                'message' => 'Je souhaiterais visiter le bien cette semaine. Quelles sont vos disponibilités ?',
                'read_at' => null,
            ]);

            // Message non lu pour la deuxième annonce (si elle existe)
            if ($listings->count() > 1) {
                Message::create([
                    'listing_id' => $listings->last()->id,
                    'name' => 'Ibrahima Bah',
                    'email' => 'ibrahima.bah@example.com',
                    'phone' => '+224 622 33 44 55',
                    'message' => 'Bonjour, pouvez-vous me confirmer que le terrain est toujours disponible ? J\'aimerais l\'acheter rapidement.',
                    'read_at' => null,
                ]);
            }
        } else {
            // Message sans annonce associée (pour démonstration)
            Message::create([
                'listing_id' => null,
                'name' => 'Sékou Touré',
                'email' => 'sekou.toure@example.com',
                'phone' => '+224 611 22 33 44',
                'message' => 'Bonjour, je cherche un appartement 2 pièces dans le quartier de Dixinn. Avez-vous quelque chose de disponible ?',
                'read_at' => null,
            ]);
        }

        // Message lu ancien
        Message::create([
            'listing_id' => $listings->first()->id ?? null,
            'name' => 'Aissatou Diallo',
            'email' => 'aissatou.diallo@example.com',
            'phone' => '+224 677 88 99 00',
            'message' => 'Merci pour les informations, je vais réfléchir et vous recontacter.',
            'read_at' => now()->subDays(1),
        ]);
    }
}
