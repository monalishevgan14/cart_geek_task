<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AgeCheck;
use App\Http\Middleware\CountryCheck;
use  \App\Http\Middleware\RoleMiddleware;

use App\Http\Middleware\EnsureTokenIsValid;
use PHPUnit\Framework\Constraint\Count;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function () {

            // ADMIN ROUTES
            Route::middleware(['web'])
                ->prefix('admin')
                ->as('admin.')
                ->group(base_path('routes/admin.php'));

            // SUPER ADMIN ROUTES
            // Route::middleware(['web','auth'])
            //     ->prefix('super-admin')
            //     ->as('superadmin.')
            //     ->group(base_path('routes/super_admin.php'));
        }
            
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register global middleware here
        $middleware->append(EnsureTokenIsValid::class);
        // $middleware->append(AgeCheck::class);

        // Register middleware groups here
        $middleware->appendToGroup('check',[
                AgeCheck::class,
                CountryCheck::class
        ]);

        $middleware->alias([
            'role' => App\Http\Middleware\RoleRedirect::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
