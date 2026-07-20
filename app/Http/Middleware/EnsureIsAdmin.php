<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restringe el acceso a rutas del panel de administrador.
 * Solo usuarios autenticados con role === 'Administrador' pueden pasar.
 * Cualquier otro usuario (o invitado) recibe 403 Acceso denegado.
 */
class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || strtolower($user->role) !== 'administrador') {
            abort(403, 'Acceso denegado: se requiere rol de Administrador.');
        }

        return $next($request);
    }
}
