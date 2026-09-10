<?php

namespace App\Services;

use App\Exceptions\LivroIndisponivelException;
use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\Usuario;

class EmprestimoService
{
    /**
     * Registra um novo empréstimo, aplicando a regra de negócio central:
     * um livro não pode ser emprestado se já tiver um empréstimo em aberto.
     * Ensinado no Encontro 4 (Organizando Controllers).
     */
    public function registrar(Livro $livro, Usuario $usuario): Emprestimo
    {
        $jaEmprestado = $livro->emprestimos()->whereNull('data_devolucao_real')->exists();

        if ($jaEmprestado) {
            throw new LivroIndisponivelException();
        }

        return Emprestimo::create([
            'livro_id' => $livro->id,
            'usuario_id' => $usuario->id,
            'data_emprestimo' => now()->toDateString(),
            'data_devolucao_prevista' => now()->addDays(14)->toDateString(),
        ])->load(['livro', 'usuario']);
    }
}
