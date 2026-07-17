<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Evita que el navegador guarde en caché (o en el back/forward cache)
 * las páginas vistas por un usuario autenticado, para que al cerrar
 * sesión y usar el botón "Atrás" no se vuelva a mostrar contenido
 * privado (perfil, dashboard, etc.) desde la caché local.
 */
class PreventBackHistoryCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (auth()->check()) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
