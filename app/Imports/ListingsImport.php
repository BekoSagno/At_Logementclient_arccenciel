<?php

namespace App\Imports;

use App\Models\Listing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ListingsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Convertir les valeurs
        $type = $this->convertTypeFromLabel($row['type'] ?? '');
        $status = $this->convertBoolean($row['publie'] ?? 'Non');
        $isFeatured = $this->convertBoolean($row['mise_en_avant'] ?? 'Non');
        
        // Générer le slug si vide
        $slug = $row['slug'] ?? Str::slug($row['titre'] ?? '');
        if (empty($slug)) {
            $slug = Str::slug($row['titre'] ?? 'listing-' . time());
        }
        
        // Vérifier l'unicité du slug
        $originalSlug = $slug;
        $counter = 1;
        while (Listing::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // Parser les dates
        $publishedAt = null;
        if (!empty($row['date_de_publication'])) {
            try {
                $publishedAt = Carbon::createFromFormat('d/m/Y H:i', $row['date_de_publication']);
            } catch (\Exception $e) {
                try {
                    $publishedAt = Carbon::parse($row['date_de_publication']);
                } catch (\Exception $e2) {
                    $publishedAt = null;
                }
            }
        }
        
        // Parser les JSON
        $images = [];
        if (!empty($row['images_json'])) {
            $decoded = json_decode($row['images_json'], true);
            if (is_array($decoded)) {
                $images = $decoded;
            }
        }
        
        $socialLinks = [];
        if (!empty($row['liens_sociaux_json'])) {
            $decoded = json_decode($row['liens_sociaux_json'], true);
            if (is_array($decoded)) {
                $socialLinks = $decoded;
            }
        }
        
        $customFields = [];
        if (!empty($row['champs_personnalises_json'])) {
            $decoded = json_decode($row['champs_personnalises_json'], true);
            if (is_array($decoded)) {
                $customFields = $decoded;
            }
        }
        
        return new Listing([
            'title' => $row['titre'] ?? '',
            'slug' => $slug,
            'description' => $row['description'] ?? '',
            'type' => $type,
            'service_status' => $row['statut_du_service'] ?? null,
            'price' => !empty($row['prix']) ? (float) $row['prix'] : null,
            'currency' => $row['devise'] ?? 'GNF',
            'address' => $row['adresse'] ?? null,
            'city' => $row['ville'] ?? null,
            'bedrooms' => !empty($row['chambres']) ? (int) $row['chambres'] : null,
            'bathrooms' => !empty($row['salles_de_bain']) ? (int) $row['salles_de_bain'] : null,
            'surface' => !empty($row['surface_m']) ? (int) $row['surface_m'] : null,
            'document_type' => $row['type_de_document'] ?? null,
            'status' => $status,
            'is_featured' => $isFeatured,
            'published_at' => $publishedAt,
            'images' => !empty($images) ? $images : null,
            'social_links' => !empty($socialLinks) ? $socialLinks : null,
            'custom_fields' => !empty($customFields) ? $customFields : null,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'type' => 'required|string|in:Résidentiel,Commercial,Terrain,Service',
        ];
    }

    /**
     * Convert type label to value
     */
    private function convertTypeFromLabel(string $label): string
    {
        return match(trim($label)) {
            'Résidentiel' => 'residential',
            'Commercial' => 'commercial',
            'Terrain' => 'land',
            'Service' => 'service',
            default => 'service',
        };
    }

    /**
     * Convert boolean string to boolean
     */
    private function convertBoolean(string $value): bool
    {
        $value = strtolower(trim($value));
        return in_array($value, ['oui', 'yes', '1', 'true', 'vrai']);
    }
}
