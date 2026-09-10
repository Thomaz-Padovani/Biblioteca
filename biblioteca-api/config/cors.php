<?php

return [
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    // em produção, aponta para o domínio do Vercel via variável de ambiente;
    // em desenvolvimento local, cai no Vite rodando em localhost:5173
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // false porque usamos token Bearer (Sanctum), não cookie de sessão —
    // se migrar para autenticação por cookie SPA, isso precisa virar true
    'supports_credentials' => false,
];
