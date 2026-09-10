<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emprestimo;

// Invokable Controller: uma ação isolada não precisa de um Controller
// com sete métodos vazios ao redor dela. Ensinado no Encontro 4.
class DevolucaoController extends Controller
{
    // PATCH /api/emprestimos/{emprestimo}/devolucao
    public function __invoke(Emprestimo $emprestimo)
    {
        if (! $emprestimo->estaEmAberto()) {
            return respostaErro('Este empréstimo já foi devolvido.');
        }

        $emprestimo->update(['data_devolucao_real' => now()->toDateString()]);

        return $emprestimo->load(['livro', 'usuario']);
    }
}
