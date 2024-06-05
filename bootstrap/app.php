<?php

use App\Events\PostEvent;
use App\Listeners\NotifyUser;
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
        //
        $middleware->alias([
            'user' => \App\Http\Middleware\UserAuthMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
    $app->register(Laravolt\Avatar\LumenServiceProvider::class);

    // $app->make(Illuminate\Contracts\Events\Dispatcher::class)->listen(
    //     App\Events\PostEvent::class,
    //     App\Listeners\NotifyUser::class
    // );
    $app->withEvents(discover: [
        __DIR__.'/../app/Listeners',
    ]);
    // Register routes...
    
    return $app;