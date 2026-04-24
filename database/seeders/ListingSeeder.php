<?php

namespace Database\Seeders;

use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listings = [
            [
                'title' => 'Locations de biens immobiliers',
                'slug' => 'locations-de-biens-immobiliers',
                'description' => 'Service professionnel de location de biens immobiliers à Conakry. Appartements, maisons et villas disponibles.',
                'type' => 'service',
                'service_status' => 'disponible',
                'price' => null,
                'currency' => 'GNF',
                'address' => 'Commune de Kaloum',
                'city' => 'Conakry',
                'status' => true,
                'is_featured' => true,
                'published_at' => now(),
                'images' => null,
                'custom_fields' => null,
                'social_links' => [
                    'facebook' => 'https://facebook.com/atlogement',
                    'linkedin' => 'https://linkedin.com/company/atlogement',
                ],
            ],
            [
                'title' => 'Ventes de biens immobiliers',
                'slug' => 'ventes-de-biens-immobiliers',
                'description' => 'Achat et vente de biens immobiliers. Maisons, appartements, terrains disponibles dans toute la Guinée.',
                'type' => 'service',
                'service_status' => 'disponible',
                'price' => null,
                'currency' => 'GNF',
                'address' => 'Commune de Matoto',
                'city' => 'Conakry',
                'status' => true,
                'is_featured' => false,
                'published_at' => now(),
                'images' => null,
                'custom_fields' => null,
                'social_links' => null,
            ],
            [
                'title' => 'Appartement 3 pièces - Matam',
                'slug' => 'appartement-3-pieces-matam',
                'description' => 'Bel appartement 3 pièces à louer dans le quartier de Matam. Proche des commerces et transports.',
                'type' => 'residential',
                'service_status' => null,
                'price' => 2500000,
                'currency' => 'GNF',
                'address' => 'Quartier Matam',
                'city' => 'Conakry',
                'status' => true,
                'is_featured' => true,
                'published_at' => now(),
                'bedrooms' => 3,
                'bathrooms' => 2,
                'surface' => 85,
                'images' => null,
                'custom_fields' => [
                    ['label' => 'Étage', 'value' => '2ème étage'],
                    ['label' => 'Ascenseur', 'value' => 'Oui'],
                ],
                'social_links' => null,
            ],
            [
                'title' => 'Terrain constructible - Ratoma',
                'slug' => 'terrain-constructible-ratoma',
                'description' => 'Terrain constructible de 500m² dans le quartier de Ratoma. Titre foncier disponible.',
                'type' => 'land',
                'service_status' => null,
                'price' => 50000000,
                'currency' => 'GNF',
                'address' => 'Quartier Ratoma',
                'city' => 'Conakry',
                'status' => true,
                'is_featured' => false,
                'published_at' => now(),
                'surface' => 500,
                'images' => null,
                'document_type' => 'titre_foncier',
                'custom_fields' => null,
                'social_links' => null,
            ],
            [
                'title' => 'Local commercial - Centre-ville',
                'slug' => 'local-commercial-centre-ville',
                'description' => 'Local commercial de 120m² idéal pour commerce, bureau ou restaurant. En plein centre-ville.',
                'type' => 'commercial',
                'service_status' => null,
                'price' => 8000000,
                'currency' => 'GNF',
                'address' => 'Centre-ville, Kaloum',
                'city' => 'Conakry',
                'status' => false,
                'is_featured' => false,
                'published_at' => null,
                'surface' => 120,
                'images' => null,
                'custom_fields' => null,
                'social_links' => null,
            ],
        ];

        foreach ($listings as $listingData) {
            Listing::create($listingData);
        }
    }
}
