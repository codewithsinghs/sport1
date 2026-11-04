<?php

use App\Modules\Core\Http\Middleware\EnsureAssociationContext;
use App\Modules\Core\Http\Middleware\EnsureUserIsActive;
use App\Modules\Core\Http\Middleware\ResolveAssociationContext;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Resolve on every web request, including Livewire update requests.
        $middleware->web(append: [
            ResolveAssociationContext::class,
        ]);

        $middleware->alias([
            'ensure.association' => EnsureAssociationContext::class,
            'ensure.active' => EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
