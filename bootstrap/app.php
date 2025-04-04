<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Square1\LaravelIdempotency\Http\Middleware\IdempotencyMiddleware;
use App\Http\Middleware\Authentication;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

       
        $middleware->redirectGuestsTo('login');

        $middleware->alias([
            'auth.custom' => Authentication::class,
            'idempotency' => IdempotencyMiddleware::class,
        ]); 

        $middleware->trustHosts(at: ['laravel.test']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
    })->create();
