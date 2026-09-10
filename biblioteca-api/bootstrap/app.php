<?php

// Este arquivo é preservado pelo processo de mesclagem descrito no README
// (a cópia do esqueleto do Laravel NUNCA sobrescreve arquivos que já
// existem aqui). Por isso, ao contrário de config/cors.php, este já é o
// bootstrap/app.php completo do projeto — não uma referência parcial.
//
// A única diferença em relação ao padrão do Laravel é o bloco
// withExceptions(...), ensinado no Encontro 4 (Respostas de API
// Consistentes): garante que qualquer erro em rota /api/* sempre
// devolva JSON, nunca uma página HTML.

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function ($request, $throwable) {
            return $request->is('api/*');
        });
    })->create();
