<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AutorController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\DevolucaoController;
use App\Http\Controllers\Api\EmprestimoController;
use App\Http\Controllers\Api\LivroController;
use App\Http\Controllers\Api\ReservaController;
use Illuminate\Support\Facades\Route;

// Prefixo de versão + rate limiting: ensinado no Encontro 4 (Rotas de API).
Route::prefix('v1')->middleware('throttle:60,1')->group(function () {

    // Rotas públicas
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/livros', [LivroController::class, 'index']);
    Route::get('/livros/{livro}', [LivroController::class, 'show']);
    Route::get('/autores', [AutorController::class, 'index']);
    Route::get('/autores/{autor}', [AutorController::class, 'show']);
    Route::get('/categorias', [CategoriaController::class, 'index']);

    // Rotas autenticadas (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        // CRUD de livros/autores/categorias — autorização fina fica no FormRequest (ehBibliotecario)
        Route::post('/livros', [LivroController::class, 'store']);
        Route::match(['put', 'patch'], '/livros/{livro}', [LivroController::class, 'update']);
        Route::delete('/livros/{livro}', [LivroController::class, 'destroy']);
        Route::post('/livros/{livro}/capa', [LivroController::class, 'uploadCapa']);
        Route::delete('/livros/{livro}/capa', [LivroController::class, 'removerCapa']);

        Route::post('/autores', [AutorController::class, 'store']);
        Route::match(['put', 'patch'], '/autores/{autor}', [AutorController::class, 'update']);
        Route::delete('/autores/{autor}', [AutorController::class, 'destroy']);

        Route::post('/categorias', [CategoriaController::class, 'store']);
        Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy']);

        // Empréstimos e reservas
        Route::get('/emprestimos', [EmprestimoController::class, 'index']);
        Route::post('/livros/{livro}/emprestimos', [EmprestimoController::class, 'store']);
        Route::patch('/emprestimos/{emprestimo}/devolucao', DevolucaoController::class);

        Route::get('/reservas', [ReservaController::class, 'index']);
        Route::post('/livros/{livro}/reservas', [ReservaController::class, 'store']);
        Route::patch('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar']);
    });
});
