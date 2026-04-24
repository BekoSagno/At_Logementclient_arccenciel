<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetFilamentLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Définir la locale seulement si intl est chargé
        // Sinon, utiliser 'en' pour éviter les erreurs avec Number::format()
        if (extension_loaded('intl')) {
            app()->setLocale('fr');
        } else {
            // Utiliser 'en' par défaut si intl n'est pas disponible
            // Cela évite l'erreur "intl extension is required"
            app()->setLocale('en');
        }
        return $next($request);
    }
}


