<?php

namespace App\Filament\Resources\ListingResource\Pages;

use App\Events\ListingCreated;
use App\Filament\Resources\ListingResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateListing extends CreateRecord
{
    protected static string $resource = ListingResource::class;

    public function mount(): void
    {
        parent::mount();
        
        // Récupérer les liens sociaux de la dernière annonce créée par l'admin
        $lastListing = \App\Models\Listing::whereNotNull('social_links')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($lastListing && !empty($lastListing->social_links)) {
            $socialLinks = $lastListing->social_links;
            
            // Pré-remplir les champs avec les liens sociaux de la dernière annonce
            $this->form->fill([
                'social_links_facebook' => $socialLinks['facebook'] ?? '',
                'social_links_linkedin' => $socialLinks['linkedin'] ?? '',
                'social_links_twitter' => $socialLinks['twitter'] ?? '',
                'social_links_instagram' => $socialLinks['instagram'] ?? '',
                'social_links_tiktok' => $socialLinks['tiktok'] ?? '',
            ]);
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Fixer la devise à GNF
        $data['currency'] = 'GNF';
        
        // Si le prix est vide, le mettre à null
        if (empty($data['price']) || $data['price'] === '') {
            $data['price'] = null;
        }
        
        // Filament gère automatiquement les fichiers temporaires Livewire
        // On nettoie juste les valeurs invalides ou null
        if (isset($data['images'])) {
            if (is_array($data['images'])) {
                // Filament convertit automatiquement les fichiers temporaires en chemins finaux
                // On filtre juste les valeurs null ou vides
                $data['images'] = array_filter($data['images'], function($image) {
                    return !empty($image) && is_string($image);
                });
                
                // Si le tableau est vide après filtrage, mettre à null
                if (empty($data['images'])) {
                    $data['images'] = null;
                } else {
                    // Réindexer le tableau
                    $data['images'] = array_values($data['images']);
                }
            } else {
                $data['images'] = null;
            }
        }
        
        // Transformer les champs social_links_* en tableau JSON
        $socialLinks = [];
        if (!empty($data['social_links_facebook'])) {
            $socialLinks['facebook'] = $data['social_links_facebook'];
        }
        if (!empty($data['social_links_linkedin'])) {
            $socialLinks['linkedin'] = $data['social_links_linkedin'];
        }
        if (!empty($data['social_links_twitter'])) {
            $socialLinks['twitter'] = $data['social_links_twitter'];
        }
        if (!empty($data['social_links_instagram'])) {
            $socialLinks['instagram'] = $data['social_links_instagram'];
        }
        if (!empty($data['social_links_tiktok'])) {
            $socialLinks['tiktok'] = $data['social_links_tiktok'];
        }
        
        $data['social_links'] = !empty($socialLinks) ? $socialLinks : [];
        
        // Supprimer les champs temporaires
        unset($data['social_links_facebook'], $data['social_links_linkedin'], $data['social_links_twitter'], $data['social_links_instagram'], $data['social_links_tiktok']);
        
        // Nettoyer les champs personnalisés vides
        if (isset($data['custom_fields']) && is_array($data['custom_fields'])) {
            $data['custom_fields'] = array_values(array_filter($data['custom_fields'], function($field) {
                return !empty($field['label']) && !empty($field['value']);
            }));
            if (empty($data['custom_fields'])) {
                $data['custom_fields'] = null;
            }
        }
        
        // S'assurer que service_status est préservé (peut être null si non applicable)
        if (!isset($data['service_status']) || $data['service_status'] === '') {
            $data['service_status'] = null;
        }
        
        // Gérer la publication programmée
        if (!empty($data['status'])) {
            // L'annonce doit être publiée
            if (!empty($data['scheduled_at'])) {
                // Publication programmée : ne pas définir published_at maintenant
                $data['published_at'] = null;
                // Vérifier que la date programmée est dans le futur
                if ($data['scheduled_at'] <= now()) {
                    // Si la date est passée, publier immédiatement
                    $data['published_at'] = now();
                    $data['scheduled_at'] = null;
                }
            } else {
                // Pas de date programmée : publier immédiatement
                $data['published_at'] = now();
                $data['scheduled_at'] = null;
            }
        } else {
            // L'annonce n'est pas publiée : réinitialiser les dates
            $data['published_at'] = null;
            $data['scheduled_at'] = null;
        }
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Compresser les images et vidéos après la création
        if ($this->record->images && is_array($this->record->images)) {
            $compressedMedia = $this->compressMedia($this->record->images);
            $this->record->update(['images' => $compressedMedia]);
        }
        
        // Déclencher l'événement si l'annonce est publiée
        if ($this->record->status && $this->record->published_at) {
            event(new ListingCreated($this->record));
        }
        
        // Créer une notification admin pour la nouvelle annonce
        try {
            \App\Services\AdminNotificationService::notifyNewListing($this->record);
        } catch (\Exception $e) {
            \Log::warning('Erreur lors de la création de la notification admin: ' . $e->getMessage());
        }
    }

    protected function compressMedia(array $mediaFiles): array
    {
        $compressedMedia = [];
        $disk = Storage::disk('public');

        foreach ($mediaFiles as $filePath) {
            try {
                // Vérifier si le fichier existe
                if (!$disk->exists($filePath)) {
                    $compressedMedia[] = $filePath;
                    continue;
                }

                // Obtenir le chemin complet du fichier
                $fullPath = $disk->path($filePath);
                
                // Vérifier si le fichier existe physiquement
                if (!file_exists($fullPath)) {
                    $compressedMedia[] = $filePath;
                    continue;
                }
                
                // Détecter si c'est une vidéo ou une image
                $mimeType = mime_content_type($fullPath);
                $isVideo = str_starts_with($mimeType, 'video/');
                $isImage = str_starts_with($mimeType, 'image/');
                
                if ($isVideo) {
                    // Compresser la vidéo
                    $compressedPath = $this->compressVideoFile($fullPath, $filePath);
                    $compressedMedia[] = $compressedPath ?: $filePath;
                } elseif ($isImage) {
                    // Compresser l'image avec meilleure qualité
                    $this->compressImageFile($fullPath);
                    // Si l'extension a changé (PNG -> JPG), mettre à jour le chemin
                    if (str_ends_with($filePath, '.png') && file_exists(preg_replace('/\.png$/i', '.jpg', $fullPath))) {
                        $filePath = preg_replace('/\.png$/i', '.jpg', $filePath);
                    }
                    $compressedMedia[] = $filePath;
                } else {
                    $compressedMedia[] = $filePath;
                }
            } catch (\Exception $e) {
                // En cas d'erreur, garder le fichier original
                \Log::warning("Erreur lors de la compression du média: {$filePath}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $compressedMedia[] = $filePath;
            }
        }

        return $compressedMedia;
    }
    
    // Alias pour compatibilité
    protected function compressImages(array $images): array
    {
        return $this->compressMedia($images);
    }

    protected function compressImageFile(string $filePath): bool
    {
        if (!function_exists('imagecreatefromjpeg') && !function_exists('imagecreatefrompng')) {
            return false; // GD n'est pas disponible
        }

        // Obtenir les informations de l'image
        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) {
            return false;
        }

        [$width, $height, $type] = $imageInfo;
        $maxWidth = 1920; // Maximum 1920px (Full HD) pour garder une excellente qualité
        $maxHeight = 1920; // Maximum 1920px de hauteur
        $quality = 85; // Qualité élevée (85/100) pour une meilleure qualité visuelle

        // Charger l'image selon son type
        $source = null;
        try {
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $source = @imagecreatefromjpeg($filePath);
                    break;
                case IMAGETYPE_PNG:
                    $source = @imagecreatefrompng($filePath);
                    break;
                case IMAGETYPE_WEBP:
                    if (function_exists('imagecreatefromwebp')) {
                        $source = @imagecreatefromwebp($filePath);
                    }
                    break;
                default:
                    return false; // Type non supporté
            }
        } catch (\Exception $e) {
            \Log::warning("Erreur lors du chargement de l'image: {$filePath}", ['error' => $e->getMessage()]);
            return false;
        }

        if (!$source) {
            return false;
        }

        // Calculer les nouvelles dimensions si nécessaire (maintenir le ratio)
        $newWidth = $width;
        $newHeight = $height;
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int) ($width * $ratio);
            $newHeight = (int) ($height * $ratio);
        }

        // Créer une nouvelle image redimensionnée
        $destination = @imagecreatetruecolor($newWidth, $newHeight);
        if (!$destination) {
            imagedestroy($source);
            return false;
        }
        
        // Préserver la transparence pour PNG
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Redimensionner l'image
        imagecopyresampled(
            $destination,
            $source,
            0, 0, 0, 0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        // Sauvegarder l'image compressée
        $success = false;
        try {
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $success = @imagejpeg($destination, $filePath, $quality);
                    break;
                case IMAGETYPE_PNG:
                    // Pour PNG, convertir en JPEG pour réduire la taille
                    $newPath = preg_replace('/\.png$/i', '.jpg', $filePath);
                    $success = @imagejpeg($destination, $newPath, $quality);
                    if ($success && file_exists($newPath)) {
                        @unlink($filePath); // Supprimer l'ancien PNG
                        $filePath = $newPath; // Mettre à jour le chemin
                    }
                    break;
                case IMAGETYPE_WEBP:
                    if (function_exists('imagewebp')) {
                        // WebP utilise une échelle de 0-100, donc on convertit
                        $webpQuality = (int) ($quality * 0.85); // 85 de qualité = ~72 pour WebP
                        $success = @imagewebp($destination, $filePath, $webpQuality);
                    }
                    break;
            }
        } catch (\Exception $e) {
            \Log::warning("Erreur lors de la sauvegarde de l'image compressée: {$filePath}", ['error' => $e->getMessage()]);
        }

        // Libérer la mémoire
        @imagedestroy($source);
        @imagedestroy($destination);

        return $success;
    }

    protected function optimizeImageFile(string $filePath): void
    {
        if (!function_exists('imagecreatefromjpeg') && !function_exists('imagecreatefrompng')) {
            return; // GD n'est pas disponible
        }

        $imageInfo = @getimagesize($filePath);
        if (!$imageInfo) {
            return;
        }

        [$width, $height, $type] = $imageInfo;
        
        // Si l'image est très grande, redimensionner
        if ($width > 1600 || $height > 1600) {
            $this->compressImageFile($filePath);
            return;
        }

        // Sinon, juste optimiser la qualité pour JPEG
        if ($type === IMAGETYPE_JPEG) {
            try {
                $source = @imagecreatefromjpeg($filePath);
                if ($source) {
                    // Ré-encoder avec qualité élevée (85) pour meilleure qualité visuelle
                    @imagejpeg($source, $filePath, 85);
                    @imagedestroy($source);
                }
            } catch (\Exception $e) {
                // Ignorer les erreurs silencieusement
            }
        }
    }
    
    /**
     * Compresser un fichier vidéo en utilisant FFmpeg
     */
    protected function compressVideoFile(string $filePath, string $originalPath): ?string
    {
        // Vérifier si FFmpeg est disponible
        $ffmpegPath = $this->getFFmpegPath();
        if (!$ffmpegPath) {
            \Log::info("FFmpeg n'est pas disponible, la vidéo ne sera pas compressée: {$filePath}");
            return null;
        }

        try {
            // Obtenir les informations de la vidéo
            $fileSize = @filesize($filePath);
            if ($fileSize === false) {
                return null;
            }

            // Ne compresser que si > 50MB
            $maxSize = 50 * 1024 * 1024; // 50MB
            if ($fileSize <= $maxSize) {
                return null; // Vidéo déjà assez petite
            }

            // Créer un fichier temporaire pour la compression
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $compressedPath = preg_replace('/\.' . preg_quote($extension, '/') . '$/i', '_compressed.mp4', $filePath);
            
            // Commande FFmpeg pour compresser avec bonne qualité
            // -crf 23 = qualité élevée (18-28: plus bas = meilleure qualité)
            // -preset medium = équilibre vitesse/compression
            // -vcodec libx264 = codec H.264
            // -acodec aac = codec audio AAC
            $command = sprintf(
                '"%s" -i "%s" -vcodec libx264 -crf 23 -preset medium -acodec aac -b:a 128k -movflags +faststart "%s" -y 2>&1',
                escapeshellarg($ffmpegPath),
                escapeshellarg($filePath),
                escapeshellarg($compressedPath)
            );

            $output = [];
            $returnVar = 0;
            @exec($command, $output, $returnVar);

            if ($returnVar === 0 && file_exists($compressedPath)) {
                $compressedSize = @filesize($compressedPath);
                // Remplacer l'original seulement si la version compressée est plus petite
                if ($compressedSize !== false && $compressedSize < $fileSize) {
                    @unlink($filePath);
                    @rename($compressedPath, $filePath);
                    
                    // Mettre à jour le chemin si nécessaire
                    $newPath = preg_replace('/_compressed\.mp4$/i', '.mp4', $originalPath);
                    return $newPath;
                } else {
                    @unlink($compressedPath);
                }
            }
        } catch (\Exception $e) {
            \Log::warning("Erreur lors de la compression de la vidéo: {$filePath}", [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }
    
    /**
     * Trouver le chemin de FFmpeg
     */
    protected function getFFmpegPath(): ?string
    {
        // Essayer différents chemins communs pour FFmpeg
        $possiblePaths = [
            'ffmpeg', // Dans le PATH
            '/usr/bin/ffmpeg',
            '/usr/local/bin/ffmpeg',
            'C:\\ffmpeg\\bin\\ffmpeg.exe',
            'C:\\Program Files\\ffmpeg\\bin\\ffmpeg.exe',
        ];

        foreach ($possiblePaths as $path) {
            if ($this->isFFmpegAvailable($path)) {
                return $path;
            }
        }

        return null;
    }
    
    /**
     * Vérifier si FFmpeg est disponible au chemin donné
     */
    protected function isFFmpegAvailable(string $path): bool
    {
        $command = sprintf('"%s" -version 2>&1', escapeshellarg($path));
        $output = [];
        $returnVar = 0;
        @exec($command, $output, $returnVar);
        return $returnVar === 0 && !empty($output);
    }
}

