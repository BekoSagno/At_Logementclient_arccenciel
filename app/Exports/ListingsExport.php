<?php

namespace App\Exports;

use App\Models\Listing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ListingsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Listing::with('messages')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Titre',
            'Slug',
            'Description',
            'Type',
            'Statut du service',
            'Prix',
            'Devise',
            'Adresse',
            'Ville',
            'Chambres',
            'Salles de bain',
            'Surface (m²)',
            'Type de document',
            'Publié',
            'Mise en avant',
            'Date de publication',
            'Images (JSON)',
            'Liens sociaux (JSON)',
            'Champs personnalisés (JSON)',
            'Date de création',
            'Date de mise à jour',
        ];
    }

    /**
     * @param Listing $listing
     * @return array
     */
    public function map($listing): array
    {
        return [
            $listing->id,
            $listing->title,
            $listing->slug,
            $listing->description,
            $this->getTypeLabel($listing->type),
            $listing->service_status ?? '',
            $listing->price ?? '',
            $listing->currency ?? 'GNF',
            $listing->address ?? '',
            $listing->city ?? '',
            $listing->bedrooms ?? '',
            $listing->bathrooms ?? '',
            $listing->surface ?? '',
            $listing->document_type ?? '',
            $listing->status ? 'Oui' : 'Non',
            $listing->is_featured ? 'Oui' : 'Non',
            $listing->published_at ? $listing->published_at->format('d/m/Y H:i') : '',
            json_encode($listing->images ?? []),
            json_encode($listing->social_links ?? []),
            json_encode($listing->custom_fields ?? []),
            $listing->created_at->format('d/m/Y H:i'),
            $listing->updated_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FBBF24'],
            ]],
        ];
    }

    /**
     * Get type label in French
     */
    private function getTypeLabel(string $type): string
    {
        return match($type) {
            'residential' => 'Résidentiel',
            'commercial' => 'Commercial',
            'land' => 'Terrain',
            'service' => 'Service',
            default => $type,
        };
    }
}
