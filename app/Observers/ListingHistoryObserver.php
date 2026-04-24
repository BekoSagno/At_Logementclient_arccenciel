<?php

namespace App\Observers;

use App\Models\Listing;
use App\Models\ListingHistory;
use Illuminate\Support\Facades\Auth;

class ListingHistoryObserver
{
    /**
     * Handle the Listing "created" event.
     */
    public function created(Listing $listing): void
    {
        try {
            ListingHistory::create([
                'listing_id' => $listing->id,
                'user_id' => Auth::id(),
                'action' => 'created',
                'old_data' => null,
                'new_data' => $listing->getAttributes(),
                'changes' => 'Annonce créée',
            ]);
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas la création de l'annonce
            \Log::error('Erreur lors de la création de l\'historique: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Listing "updated" event.
     */
    public function updated(Listing $listing): void
    {
        try {
            $changes = $listing->getChanges();
            $original = $listing->getOriginal();
            
            // Filtrer les changements pertinents (exclure updated_at)
            $relevantChanges = array_diff_key($changes, ['updated_at' => '']);
            
            if (!empty($relevantChanges)) {
                $oldData = [];
                $newData = [];
                $changeDescriptions = [];
                
                foreach ($relevantChanges as $key => $newValue) {
                    $oldData[$key] = $original[$key] ?? null;
                    $newData[$key] = $newValue;
                    
                    // Créer une description lisible du changement
                    $oldValue = $oldData[$key];
                    if ($key === 'status') {
                        $changeDescriptions[] = sprintf(
                            'Statut: %s → %s',
                            $oldValue ? 'Publié' : 'Brouillon',
                            $newValue ? 'Publié' : 'Brouillon'
                        );
                    } elseif ($key === 'published_at') {
                        $changeDescriptions[] = sprintf(
                            'Date de publication: %s → %s',
                            $oldValue ? $oldValue : 'Non définie',
                            $newValue ? $newValue : 'Non définie'
                        );
                    } elseif ($key === 'scheduled_at') {
                        $changeDescriptions[] = sprintf(
                            'Publication programmée: %s → %s',
                            $oldValue ? $oldValue : 'Non définie',
                            $newValue ? $newValue : 'Non définie'
                        );
                    } else {
                        $changeDescriptions[] = sprintf(
                            '%s: %s → %s',
                            ucfirst(str_replace('_', ' ', $key)),
                            $oldValue ?? 'vide',
                            $newValue ?? 'vide'
                        );
                    }
                }
                
                ListingHistory::create([
                    'listing_id' => $listing->id,
                    'user_id' => Auth::id(),
                    'action' => 'updated',
                    'old_data' => $oldData,
                    'new_data' => $newData,
                    'changes' => implode(', ', $changeDescriptions),
                ]);
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas la mise à jour de l'annonce
            \Log::error('Erreur lors de la mise à jour de l\'historique: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Listing "deleted" event.
     */
    public function deleted(Listing $listing): void
    {
        try {
            ListingHistory::create([
                'listing_id' => $listing->id,
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'old_data' => $listing->getAttributes(),
                'new_data' => null,
                'changes' => 'Annonce supprimée',
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression de l\'historique: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Listing "restored" event.
     */
    public function restored(Listing $listing): void
    {
        try {
            ListingHistory::create([
                'listing_id' => $listing->id,
                'user_id' => Auth::id(),
                'action' => 'restored',
                'old_data' => null,
                'new_data' => $listing->getAttributes(),
                'changes' => 'Annonce restaurée',
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la restauration de l\'historique: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Listing "force deleted" event.
     */
    public function forceDeleted(Listing $listing): void
    {
        try {
            ListingHistory::create([
                'listing_id' => $listing->id,
                'user_id' => Auth::id(),
                'action' => 'force_deleted',
                'old_data' => $listing->getAttributes(),
                'new_data' => null,
                'changes' => 'Annonce supprimée définitivement',
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la suppression définitive de l\'historique: ' . $e->getMessage());
        }
    }
}
