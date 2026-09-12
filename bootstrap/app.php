<?php

use App\Http\Middleware\SetTeamUrlDefaults;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetTeamUrlDefaults::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->respond(function (Response $response): Response {
            if ($response->getStatusCode() !== 419 || request()->expectsJson()) {
                return $response;
            }

            return redirect()
                ->back()
                ->withInput(request()->except([
                    '_token',
                    'password',
                    'password_confirmation',
                    'current_password',
                    'code',
                    'recovery_code',
                ]))
                ->with('error', 'Sesi Anda telah berakhir. Silakan periksa kembali formulir lalu kirim ulang.');
        });
    })->create();
