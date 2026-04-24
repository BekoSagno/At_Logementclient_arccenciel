<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PublishListingToMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listing:publish-to-make 
                            {--webhook=https://hook.eu1.make.com/kdzl78yqt6kdbzuwb25leedh2n51qdof : URL du webhook Make.com}
                            {--titre= : Titre de l\'annonce}
                            {--description= : Description de l\'annonce}
                            {--prix= : Prix de l\'annonce}
                            {--adresse= : Adresse de l\'annonce}
                            {--url-image= : URL de l\'image}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publier une annonce vers Make.com via webhook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $webhookUrl = $this->option('webhook');
        $titre = $this->option('titre') ?: '✨ Sublime Villa Contemporaine avec Piscine - Kipé';
        $description = $this->option('description') ?: 'Découvrez cette villa d\'exception offrant des prestations haut de gamme. Salon spacieux, cuisine équipée et jardin arboré. Un havre de paix au cœur de Conakry. #Immobilier #Luxe #Conakry #Vente';
        $prix = $this->option('prix') ?: '2500000000';
        $adresse = $this->option('adresse') ?: 'Kipé Centre, Conakry';
        $urlImage = $this->option('url-image') ?: 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1200&q=80';

        // Formater le prix
        $prixFormate = number_format((float) $prix, 0, ',', '.') . ' GNF';

        // Préparer les données au format demandé
        $data = [
            'inscription' => [
                'titre' => $titre,
                'description' => $description,
                'prix_formate' => $prixFormate,
                'adresse' => $adresse,
                'URL' => $urlImage,
            ]
        ];

        $this->info('Envoi de l\'annonce vers Make.com...');
        $this->line('Webhook: ' . $webhookUrl);
        $this->line('Titre: ' . $titre);
        $this->line('Prix: ' . $prixFormate);
        $this->line('');

        try {
            $response = Http::timeout(30)
                ->retry(2, 1000)
                ->post($webhookUrl, $data);

            if ($response->successful()) {
                $this->info('✅ Annonce publiée avec succès vers Make.com !');
                Log::info('Annonce publiée avec succès vers Make.com', [
                    'titre' => $titre,
                    'webhook' => $webhookUrl,
                    'response_status' => $response->status(),
                ]);
                return Command::SUCCESS;
            } else {
                $this->error('❌ Erreur lors de l\'envoi: ' . $response->status());
                $this->error('Réponse: ' . $response->body());
                Log::error('Erreur lors de l\'envoi vers Make.com', [
                    'titre' => $titre,
                    'webhook' => $webhookUrl,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
            Log::error('Exception lors de l\'envoi vers Make.com', [
                'titre' => $titre,
                'webhook' => $webhookUrl,
                'error' => $e->getMessage(),
            ]);
            return Command::FAILURE;
        }
    }
}
