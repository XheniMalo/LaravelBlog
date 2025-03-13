<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Authentication;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

       
        $middleware->redirectGuestsTo('login');

        $middleware->alias([
            'auth.custom' => Authentication::class,
        ]); 

        $middleware->trustHosts(at: ['laravel.test']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
    })->create();
