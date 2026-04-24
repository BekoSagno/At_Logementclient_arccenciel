<?php

namespace App\Console\Commands;

use App\Events\ListingCreated;
use App\Models\Listing;
use Illuminate\Console\Command;

class PublishScheduledListings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listings:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publie automatiquement les annonces programmées dont la date/heure est atteinte';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        
        // Récupérer les annonces programmées dont la date/heure est passée et qui ne sont pas encore publiées
        // Une annonce programmée a status=true, scheduled_at défini, mais published_at=null
        $listings = Listing::whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->where('status', true)
            ->whereNull('published_at')
            ->get();
        
        if ($listings->isEmpty()) {
            $this->info('Aucune annonce à publier.');
            \Log::info('PublishScheduledListings: Aucune annonce à publier à ' . $now->format('Y-m-d H:i:s'));
            return 0;
        }
        
        $count = 0;
        foreach ($listings as $listing) {
            try {
                // Publier l'annonce en définissant published_at à la date programmée
                $listing->update([
                    'published_at' => $listing->scheduled_at, // Utiliser la date programmée
                    'scheduled_at' => null, // Réinitialiser après publication
                ]);
                
                // Rafraîchir le modèle pour avoir les dernières données
                $listing->refresh();
                
                // Déclencher l'événement pour notifier les utilisateurs
                event(new ListingCreated($listing));
                
                // Créer une notification admin pour la publication réussie
                try {
                    \App\Services\AdminNotificationService::notifyListingPublished($listing);
                } catch (\Exception $e) {
                    \Log::warning('Erreur lors de la création de la notification admin pour la publication: ' . $e->getMessage());
                }
                
                $count++;
                $this->info("Annonce '{$listing->title}' (ID: {$listing->id}) publiée avec succès à {$listing->published_at->format('d/m/Y H:i')}.");
                \Log::info("Annonce '{$listing->title}' (ID: {$listing->id}) publiée avec succès via la tâche planifiée.");
            } catch (\Exception $e) {
                $this->error("Erreur lors de la publication de l'annonce '{$listing->title}' (ID: {$listing->id}): " . $e->getMessage());
                \Log::error("Erreur lors de la publication de l'annonce '{$listing->title}' (ID: {$listing->id}): " . $e->getMessage());
            }
        }
        
        $this->info("{$count} annonce(s) publiée(s) avec succès.");
        return 0;
    }
}
