<?php

namespace App\Providers;

use App\Models\Listing;
use App\Models\Message;
use App\Observers\ListingObserver;
use App\Observers\ListingHistoryObserver;
use App\Observers\MessageObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Number;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Si intl n'est pas chargé, créer un macro pour Number::format()
        // Note: Les macros ne peuvent pas remplacer les méthodes statiques,
        // mais on peut créer une méthode alternative
        if (!extension_loaded('intl')) {
            Number::macro('formatWithoutIntl', function (int|float $number, ?int $precision = null, ?int $maxPrecision = null, ?string $locale = null) {
                if ($precision !== null) {
                    $number = round($number, $precision);
                    return number_format($number, $precision, ',', ' ');
                } elseif ($maxPrecision !== null) {
                    $number = round($number, $maxPrecision);
                    return number_format($number, $maxPrecision, ',', ' ');
                }
                return number_format($number, 0, ',', ' ');
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Augmenter les limites pour les uploads de fichiers volumineux
        // Note: upload_max_filesize et post_max_size doivent être configurés dans php.ini
        // Ils ne peuvent pas être modifiés via ini_set() une fois PHP démarré
        @ini_set('max_execution_time', '600');
        @ini_set('max_input_time', '600');
        @ini_set('memory_limit', '512M');
        
        // Enregistrer les observers
        Message::observe(MessageObserver::class);
        Listing::observe(ListingObserver::class);
        Listing::observe(ListingHistoryObserver::class);
    }
}
