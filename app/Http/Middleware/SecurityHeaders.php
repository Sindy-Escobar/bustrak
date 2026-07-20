<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Agrega encabezados de seguridad HTTP a todas las respuestas del sistema.
 *
 * Corrige la prueba de seguridad #6 de la Matriz de Auditoría:
 * "Verificar presencia de encabezados de seguridad HTTP"
 *
 * Encabezados agregados:
 *  - X-Frame-Options: SAMEORIGIN           → previene clickjacking
 *  - X-Content-Type-Options: nosniff       → previene MIME-type sniffing
 *  - X-XSS-Protection: 1; mode=block       → protección adicional XSS (navegadores legacy)
 *  - Referrer-Policy                        → controla qué info se envía al referrer
 *  - Content-Security-Policy               → restringe fuentes de recursos cargados
 *  - Strict-Transport-Security             → fuerza HTTPS en conexiones futuras
 *  - Permissions-Policy                    → deshabilita APIs del navegador no usadas
 *
 * Encabezados eliminados:
 *  - X-Powered-By                          → no revelar versión de PHP/servidor
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ─── Eliminar encabezados que revelan tecnología del servidor ────────
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // ─── Prevenir Clickjacking ───────────────────────────────────────────
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // ─── Prevenir MIME-type Sniffing ─────────────────────────────────────
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ─── Protección XSS para navegadores legacy ──────────────────────────
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ─── Política de Referrer ─────────────────────────────────────────────
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ─── Política de Permisos (deshabilitar APIs no usadas) ─────────────
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=()'
        );

        // ─── Content Security Policy básica ──────────────────────────────────
        // Permite scripts/estilos del mismo origen y CDNs utilizados en el proyecto.
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:; " .
            "img-src 'self' data: https: blob:; " .
            "connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.gstatic.com; " .
            "worker-src 'self' blob:; " .
            "frame-ancestors 'self';"
        );

        // ─── Forzar HTTPS (HSTS) ──────────────────────────────────────────────
        // Solo aplica si la solicitud llega por HTTPS.
        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
