<?php

namespace App\Filament\Resources;

/**
 * Configuration des champs spécifiques pour chaque type de service
 */
class ListingServiceConfig
{
    /**
     * Définit si le service nécessite un statut (recherche/propose/réalisé)
     */
    public static function requiresServiceStatus(string $title): bool
    {
        $servicesWithStatus = [
            'Service de nettoyage',
            'Service de transport',
            'Frigoriste-SOS-24/7',
            'Plomberie-SOS-24/7',
            'Electricité-SOS-24/7',
            'Rénovation et achèvement',
            'Etat des lieux',
            'Gestion de biens immobiliers',
        ];
        
        return in_array($title, $servicesWithStatus);
    }

    /**
     * Définit si le service nécessite un prix
     */
    public static function requiresPrice(string $title): bool
    {
        // Tous les services peuvent avoir un prix, mais ce n'est pas obligatoire
        return true;
    }

    /**
     * Retourne les champs spécifiques suggérés pour un service donné
     */
    public static function getSuggestedCustomFields(string $title): array
    {
        $suggestions = [
            'Locations de biens immobiliers' => [
                ['label' => 'Durée de location', 'example' => '12 mois'],
                ['label' => 'Caution', 'example' => '3 mois de loyer'],
                ['label' => 'Disponibilité', 'example' => 'Immédiate'],
            ],
            'Ventes de biens immobiliers' => [
                ['label' => 'Type de bien', 'example' => 'Villa, Appartement...'],
                ['label' => 'État', 'example' => 'Neuf, Bon état...'],
                ['label' => 'Disponibilité', 'example' => 'Immédiate'],
            ],
            'Promotion immobilière' => [
                ['label' => 'Phase du projet', 'example' => 'En construction, Livraison...'],
                ['label' => 'Nombre d\'unités', 'example' => '50 appartements'],
                ['label' => 'Livraison prévue', 'example' => '2025'],
            ],
            'Etat des lieux' => [
                ['label' => 'Type de bien', 'example' => 'Appartement, Villa...'],
                ['label' => 'Surface', 'example' => '120 m²'],
                ['label' => 'Date', 'example' => '15/01/2025'],
            ],
            'Gestion de biens immobiliers' => [
                ['label' => 'Nombre de biens', 'example' => '5 propriétés'],
                ['label' => 'Type de gestion', 'example' => 'Complète, Locative...'],
                ['label' => 'Durée du contrat', 'example' => '12 mois'],
            ],
            'Elaboration de contrat de location' => [
                ['label' => 'Type de contrat', 'example' => 'Location vide, meublé...'],
                ['label' => 'Durée', 'example' => '3 ans'],
                ['label' => 'Juridiction', 'example' => 'Conakry'],
            ],
            'Conseil Immobilier' => [
                ['label' => 'Type de conseil', 'example' => 'Investissement, Achat...'],
                ['label' => 'Durée', 'example' => '1 mois'],
                ['label' => 'Support', 'example' => 'Consultation, Suivi...'],
            ],
            'Rénovation et achèvement' => [
                ['label' => 'Type de travaux', 'example' => 'Rénovation complète, Finitions...'],
                ['label' => 'Surface', 'example' => '150 m²'],
                ['label' => 'Durée estimée', 'example' => '3 mois'],
            ],
            'Service de nettoyage' => [
                ['label' => 'Type de nettoyage', 'example' => 'Résidentiel, Commercial...'],
                ['label' => 'Fréquence', 'example' => 'Quotidien, Hebdomadaire...'],
                ['label' => 'Surface', 'example' => '200 m²'],
            ],
            'Service de transport' => [
                ['label' => 'Type de transport', 'example' => 'Déménagement, Livraison...'],
                ['label' => 'Distance', 'example' => '50 km'],
                ['label' => 'Type de véhicule', 'example' => 'Camion, Fourgon...'],
            ],
            'Frigoriste-SOS-24/7' => [
                ['label' => 'Type d\'intervention', 'example' => 'Réparation, Installation...'],
                ['label' => 'Type d\'appareil', 'example' => 'Climatiseur, Réfrigérateur...'],
                ['label' => 'Urgence', 'example' => 'Immédiate, Sous 24h...'],
            ],
            'Plomberie-SOS-24/7' => [
                ['label' => 'Type d\'intervention', 'example' => 'Fuite, Installation...'],
                ['label' => 'Zone', 'example' => 'Cuisine, Salle de bain...'],
                ['label' => 'Urgence', 'example' => 'Immédiate, Sous 24h...'],
            ],
            'Electricité-SOS-24/7' => [
                ['label' => 'Type d\'intervention', 'example' => 'Panne, Installation...'],
                ['label' => 'Puissance', 'example' => '220V, 380V...'],
                ['label' => 'Urgence', 'example' => 'Immédiate, Sous 24h...'],
            ],
        ];

        return $suggestions[$title] ?? [];
    }
}


