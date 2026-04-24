<?php

namespace App\Exports;

use App\Models\Message;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MessagesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Message::with('listing')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Annonce (Titre)',
            'Nom complet',
            'Email',
            'Téléphone',
            'Message',
            'Lu',
            'Date de lecture',
            'Réponse admin',
            'Date de réponse',
            'Date de réception',
            'Date de mise à jour',
        ];
    }

    /**
     * @param Message $message
     * @return array
     */
    public function map($message): array
    {
        return [
            $message->id,
            $message->listing ? $message->listing->title : 'N/A',
            $message->name,
            $message->email,
            $message->phone,
            $message->message ?? '',
            $message->read_at ? 'Oui' : 'Non',
            $message->read_at ? $message->read_at->format('d/m/Y H:i') : '',
            $message->admin_response ?? '',
            $message->response_sent_at ? $message->response_sent_at->format('d/m/Y H:i') : '',
            $message->created_at->format('d/m/Y H:i'),
            $message->updated_at->format('d/m/Y H:i'),
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
}
