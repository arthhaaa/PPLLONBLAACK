<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

    // Redirect otomatis ke login kalau belum login
    $middleware->redirectGuestsTo(fn () => route('login'));

    // Railway terminates HTTPS at the proxy, so Laravel must trust forwarded
    // headers to generate HTTPS asset URLs and keep session redirects stable.
    $middleware->trustProxies(at: '*');

    // Middleware Alias
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);

    $middleware->validateCsrfTokens(except: [
        'midtrans/webhook',
    ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
