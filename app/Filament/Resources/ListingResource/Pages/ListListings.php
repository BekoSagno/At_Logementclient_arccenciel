<?php

namespace App\Filament\Resources\ListingResource\Pages;

use App\Filament\Resources\ListingResource;
use App\Exports\ListingsExport;
use App\Imports\ListingsImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListListings extends ListRecords
{
    protected static string $resource = ListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('export')
                ->label('Exporter (Excel)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    return Excel::download(new ListingsExport, 'annonces-' . now()->format('Y-m-d-His') . '.xlsx');
                }),
            Actions\Action::make('import')
                ->label('Importer (Excel)')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('info')
                ->form([
                    FileUpload::make('file')
                        ->label('Fichier Excel')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required()
                        ->helperText('Format accepté : .xlsx ou .xls'),
                ])
                ->action(function (array $data) {
                    try {
                        Excel::import(new ListingsImport, $data['file']);
                        
                        Notification::make()
                            ->success()
                            ->title('Import réussi')
                            ->body('Les annonces ont été importées avec succès.')
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Erreur d\'import')
                            ->body('Une erreur est survenue lors de l\'import : ' . $e->getMessage())
                            ->send();
                    }
                }),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50, 100];
    }
}


