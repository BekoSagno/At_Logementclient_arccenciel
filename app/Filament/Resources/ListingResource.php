<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListingResource\Pages;
use App\Filament\Resources\ListingServiceConfig;
use App\Models\Listing;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid as SchemaGrid;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Components\Utilities\Get as SchemaGet;
use Filament\Schemas\Components\Utilities\Set as SchemaSet;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;

    protected static ?string $navigationLabel = 'Annonces';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Annonce';

    protected static ?string $pluralModelLabel = 'Annonces';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                SchemaSection::make('Informations générales')
                    ->schema([
                        SchemaGrid::make(2)
                            ->schema([
                                Forms\Components\Select::make('title')
                                    ->label('Titre')
                                    ->required()
                                    ->options([
                                        'Locations de biens immobiliers' => 'Locations de biens immobiliers',
                                        'Ventes de biens immobiliers' => 'Ventes de biens immobiliers',
                                        'Promotion immobilière' => 'Promotion immobilière',
                                        'Etat des lieux' => 'Etat des lieux',
                                        'Gestion de biens immobiliers' => 'Gestion de biens immobiliers',
                                        'Elaboration de contrat de location' => 'Elaboration de contrat de location',
                                        'Conseil Immobilier' => 'Conseil Immobilier',
                                        'Rénovation et achèvement' => 'Rénovation et achèvement',
                                        'Service de nettoyage' => 'Service de nettoyage',
                                        'Service de transport' => 'Service de transport',
                                        'Frigoriste-SOS-24/7' => 'Frigoriste-SOS-24/7',
                                        'Plomberie-SOS-24/7' => 'Plomberie-SOS-24/7',
                                        'Electricité-SOS-24/7' => 'Electricité-SOS-24/7',
                                    ])
                                    ->native(false)
                                    ->searchable(false)
                                    ->selectablePlaceholder(false)
                                    ->live()
                                    ->afterStateUpdated(function (SchemaSet $set, $state) {
                                        if ($state) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('type')
                            ->label('Type d\'annonce')
                            ->required()
                            ->options([
                                'residential' => 'Résidentiel',
                                'commercial' => 'Commercial',
                                'land' => 'Terrain',
                                'service' => 'Service',
                            ])
                            ->native(false)
                            ->default('service')
                            ->helperText('Sélectionnez le type d\'annonce'),

                        Forms\Components\Select::make('service_status')
                            ->label('Statut du service')
                            ->options([
                                'recherche' => 'Recherche',
                                'propose' => 'Propose',
                                'realise' => 'Réalisé',
                            ])
                            ->native(false)
                            ->visible(fn (SchemaGet $get) => ListingServiceConfig::requiresServiceStatus($get('title') ?? '')),

                        SchemaGrid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Prix (optionnel)')
                                    ->numeric()
                                    ->prefix('GNF')
                                    ->step(1)
                                    ->minValue(0)
                                    ->maxValue(999999999999)
                                    ->extraInputAttributes([
                                        'style' => 'text-align: left; min-width: 300px; width: 100%;',
                                        'pattern' => '[0-9]{1,12}',
                                        'maxlength' => '12',
                                        'inputmode' => 'numeric',
                                    ]),

                                Forms\Components\Hidden::make('currency')
                                    ->default('GNF'),
                            ]),
                    ]),

                SchemaSection::make('Localisation')
                    ->schema([
                        SchemaGrid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('address')
                                    ->label('Adresse')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('city')
                                    ->label('Ville')
                                    ->maxLength(255),
                            ]),
                    ]),

                SchemaSection::make('Champs personnalisés (optionnel - 3 maximum)')
                    ->description('Ajoutez jusqu\'à 3 champs personnalisés avec un titre et une valeur')
                    ->schema([
                        Forms\Components\Repeater::make('custom_fields')
                            ->label('Champs personnalisés')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Titre du champ')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('value')
                                    ->label('Valeur')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->minItems(0)
                            ->maxItems(3)
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                SchemaSection::make('Médias (Images et Vidéos)')
                    ->description('Téléchargez des images ou des vidéos. Les images peuvent être rognées.')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Images et Vidéos')
                            ->multiple()
                            ->reorderable()
                            ->directory('listings')
                            ->disk('public')
                            ->maxSize(102400) // 100MB max par fichier (en KB) pour les vidéos
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'video/mp4',
                                'video/quicktime',
                                'video/x-msvideo', // AVI
                                'video/webm',
                            ])
                            ->imageEditor() // Activer l'éditeur d'image pour permettre le rognage (uniquement pour les images)
                            ->imageEditorAspectRatios([
                                null, // Libre
                                '16:9',
                                '4:3',
                                '1:1', // Carré
                            ])
                            ->imageCropAspectRatio('16:9') // Ratio par défaut pour les images d'annonces
                            ->columnSpanFull()
                            ->extraAttributes([
                                'style' => 'max-width: 100%; overflow-x: hidden;'
                            ]),
                    ])
                    ->collapsible(),

                SchemaSection::make('Liens Réseaux Sociaux (Optionnel)')
                    ->description('Ajoutez les liens vers les publications de cette annonce sur vos réseaux sociaux')
                    ->schema([
                        Forms\Components\TextInput::make('social_links_facebook')
                            ->label('Facebook URL')
                            ->url()
                            ->placeholder('https://www.facebook.com/...')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('social_links_linkedin')
                            ->label('LinkedIn URL')
                            ->url()
                            ->placeholder('https://www.linkedin.com/...')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('social_links_twitter')
                            ->label('X (Twitter) URL')
                            ->url()
                            ->placeholder('https://x.com/...')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('social_links_instagram')
                            ->label('Instagram URL')
                            ->url()
                            ->placeholder('https://www.instagram.com/...')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('social_links_tiktok')
                            ->label('TikTok URL')
                            ->url()
                            ->placeholder('https://www.tiktok.com/...')
                            ->maxLength(255),
                    ])
                    ->collapsed()
                    ->columns(2),

                SchemaSection::make('Publication')
                    ->description('Configurez la publication de votre annonce')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label('Publier l\'annonce')
                            ->default(true)
                            ->live()
                            ->helperText('L\'annonce sera publiée immédiatement. Désactivez cette option pour créer un brouillon. Vous pouvez également programmer la publication ci-dessous.')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_available')
                            ->label('Annonce disponible')
                            ->default(true)
                            ->helperText('Désactivez cette option pour marquer l\'annonce comme non disponible. L\'annonce restera visible sur le site avec un message "Non disponible".')
                            ->columnSpanFull(),

                        SchemaGrid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('scheduled_at')
                                    ->label('📅 Date et Heure de publication (optionnel)')
                                    ->native(false)
                                    ->displayFormat('d/m/Y H:i')
                                    ->format('Y-m-d H:i:s')
                                    ->seconds(false)
                                    ->timezone('Africa/Conakry')
                                    ->minDate(now())
                                    ->helperText('Laissez vide pour publier immédiatement. Sinon, sélectionnez la date et l\'heure exacte de publication programmée.')
                                    ->visible(fn (SchemaGet $get) => $get('status') === true)
                                    ->nullable()
                                    ->extraAttributes([
                                        'class' => 'scheduled-datetime-picker',
                                        'data-enable-time' => 'true',
                                        'data-time-24hr' => 'true',
                                    ])
                                    ->extraInputAttributes([
                                        'data-enable-time' => 'true',
                                        'data-time-24hr' => 'true',
                                    ]),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('⭐ Mise en avant')
                                    ->default(false)
                                    ->helperText('Afficher cette annonce en priorité sur la page d\'accueil')
                                    ->visible(fn (SchemaGet $get) => $get('status') === true),
                            ])
                            ->visible(fn (SchemaGet $get) => $get('status') === true),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Date de publication actuelle')
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (SchemaGet $get, $record) => $record && $record->published_at && $get('status') === true)
                            ->default(fn ($record) => $record?->published_at)
                            ->helperText('Date à laquelle l\'annonce a été publiée (modifiable uniquement via la programmation)')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->orderBy('is_featured', 'desc') // Mises en avant en premier
                ->orderBy('updated_at', 'desc') // Puis par date de mise à jour (les plus récentes mises en avant en premier)
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc'))
            ->defaultPaginationPageOption(25)
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->size(50)
                    ->getStateUsing(function ($record) {
                        if (!$record->images) {
                            return null;
                        }
                        $images = is_array($record->images) ? $record->images : (is_string($record->images) ? json_decode($record->images, true) : []);
                        $firstImage = $images[0] ?? null;
                        if ($firstImage && !str_starts_with($firstImage, 'http') && !str_starts_with($firstImage, '/')) {
                            return Storage::disk('public')->url($firstImage);
                        }
                        return $firstImage;
                    })
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->extraAttributes(['loading' => 'lazy']),

                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'residential' => 'success',
                        'commercial' => 'warning',
                        'land' => 'info',
                        'service' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'residential' => 'Résidentiel',
                        'commercial' => 'Commercial',
                        'land' => 'Terrain',
                        'service' => 'Service',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 0, ',', ' ') . ' GNF')
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Statut')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Date de publication')
                    ->formatStateUsing(fn ($state): string => $state ? $state->format('d/m/Y H:i') : '')
                    ->sortable(),

                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('📅 Programmé')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-clock')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$state) return null;
                        $now = now();
                        if ($state <= $now && !$record->published_at) {
                            return 'En attente';
                        }
                        return $state->format('d/m/Y à H:i');
                    })
                    ->tooltip(function ($state, $record) {
                        if (!$state) return null;
                        return 'Cette annonce sera publiée automatiquement le ' . $state->format('d/m/Y à H:i');
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'residential' => 'Résidentiel',
                        'commercial' => 'Commercial',
                        'land' => 'Terrain',
                        'service' => 'Service',
                    ])
                    ->native(false),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Statut')
                    ->placeholder('Tous')
                    ->trueLabel('Publiés')
                    ->falseLabel('Brouillons'),

                Tables\Filters\Filter::make('scheduled')
                    ->label('Annonces programmées')
                    ->query(fn ($query) => $query->whereNotNull('scheduled_at'))
                    ->toggle(),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Mises en avant')
                    ->query(fn ($query) => $query->where('is_featured', true))
                    ->toggle(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\Action::make('toggleAvailability')
                    ->label(fn (Listing $record): string => $record->is_available ? 'Désactiver' : 'Activer')
                    ->icon(fn (Listing $record): string => $record->is_available ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Listing $record): string => $record->is_available ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Listing $record): string => $record->is_available ? 'Désactiver l\'annonce' : 'Activer l\'annonce')
                    ->modalDescription(fn (Listing $record): string => $record->is_available 
                        ? 'Cette annonce sera marquée comme non disponible. Elle restera visible sur le site avec un message "Non disponible".'
                        : 'Cette annonce sera marquée comme disponible et sera visible normalement sur le site.')
                    ->modalSubmitActionLabel(fn (Listing $record): string => $record->is_available ? 'Désactiver' : 'Activer')
                    ->action(function (Listing $record) {
                        $record->update([
                            'is_available' => !$record->is_available
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title($record->is_available ? 'Annonce activée' : 'Annonce désactivée')
                            ->body($record->is_available 
                                ? 'L\'annonce est maintenant disponible sur le site.'
                                : 'L\'annonce est maintenant marquée comme non disponible.')
                            ->success()
                            ->send();
                    }),
                Actions\Action::make('preview')
                    ->label('Prévisualiser')
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->url(fn (Listing $record): string => route('admin.listings.preview', $record))
                    ->openUrlInNewTab(),
                Actions\Action::make('duplicate')
                    ->label('Dupliquer')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Dupliquer l\'annonce')
                    ->modalDescription('Voulez-vous créer une copie de cette annonce ? Le nouveau slug sera généré automatiquement et l\'annonce sera créée en brouillon.')
                    ->modalSubmitActionLabel('Dupliquer')
                    ->modalWidth('sm')
                    ->modalIcon('heroicon-o-document-duplicate')
                    ->modalIconColor('info')
                    ->action(function (Listing $record) {
                        // Créer une nouvelle annonce avec les mêmes données
                        $newListing = $record->replicate();
                        
                        // Générer un nouveau slug unique
                        $baseSlug = Str::slug($record->title);
                        $slug = $baseSlug;
                        $counter = 1;
                        
                        while (Listing::where('slug', $slug)->exists()) {
                            $slug = $baseSlug . '-' . $counter;
                            $counter++;
                        }
                        
                        $newListing->slug = $slug;
                        
                        // Réinitialiser les champs de publication
                        $newListing->status = false;
                        $newListing->is_featured = false;
                        $newListing->published_at = now();
                        
                        // Copier les images (références seulement, pas les fichiers)
                        // Les images sont stockées comme chemins, donc on peut les copier directement
                        if ($record->images && is_array($record->images)) {
                            $newListing->images = $record->images;
                        } else {
                            $newListing->images = null;
                        }
                        
                        // Sauvegarder la nouvelle annonce
                        $newListing->save();
                        
                        // Rediriger vers la page d'édition de la nouvelle annonce
                        return redirect(static::getUrl('edit', ['record' => $newListing->id]));
                    })
                    ->successNotification(
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Annonce dupliquée avec succès')
                            ->body('Vous pouvez maintenant modifier la nouvelle annonce.')
                    ),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\ListingResource\RelationManagers\HistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListListings::route('/'),
            'create' => Pages\CreateListing::route('/create'),
            'edit' => Pages\EditListing::route('/{record}/edit'),
        ];
    }
}

