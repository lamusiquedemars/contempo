<?php

use App\Console\Commands\MaracujaDoctorCommand;
use App\Console\Commands\RetryCremonaDeliveriesCommand;
use App\Console\Commands\SendPendingAudienceMessagesCommand;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        MaracujaDoctorCommand::class,
        SendPendingAudienceMessagesCommand::class,
        RetryCremonaDeliveriesCommand::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'webhooks/brevo/audience/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
