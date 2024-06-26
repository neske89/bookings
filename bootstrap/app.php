<?php

use App\Exception\RoomIsAlreadyFullyBookedException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (RoomIsAlreadyFullyBookedException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        });
        $exceptions->render(function (LogicException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        });
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        });
    })->create();
