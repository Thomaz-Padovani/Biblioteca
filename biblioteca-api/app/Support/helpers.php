<?php

use Illuminate\Http\JsonResponse;

if (! function_exists('respostaErro')) {
    /**
     * Formato padrão de erro da API: toda rota, em qualquer Controller,
     * devolve o erro no mesmo formato { "erro": "..." }.
     * Ensinado no Encontro 4 (Respostas de API Consistentes).
     */
    function respostaErro(string $mensagem, int $status = 422): JsonResponse
    {
        return response()->json(['erro' => $mensagem], $status);
    }
}
