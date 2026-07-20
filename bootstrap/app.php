<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\PreventBackHistoryCache;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'user.active' => \App\Http\Middleware\CheckUserActive::class,
            'admin' => \App\Http\Middleware\EnsureIsAdmin::class,
        ]);

        // Evita que páginas privadas queden en caché del navegador
        // (soluciona: perfil visible con el botón "Atrás" tras logout)
        $middleware->web(append: [
            PreventBackHistoryCache::class,
            SecurityHeaders::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        // Reemplaza la pantalla técnica "419 Page Expired" por un mensaje
        // propio del sistema, en español, y regresa al usuario al formulario
        // (ej. login) que estaba llenando en vez de una página de error aislada.
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'email' => 'Tu sesión expiró por inactividad. Por favor, inténtalo de nuevo.',
                ]);
        });
    })->create();
