<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprestimo extends Model
{
    use HasFactory;

    protected $fillable = ['livro_id', 'usuario_id', 'data_emprestimo', 'data_devolucao_prevista', 'data_devolucao_real'];

    protected function casts(): array
    {
        return [
            'data_emprestimo' => 'date',
            'data_devolucao_prevista' => 'date',
            'data_devolucao_real' => 'date',
        ];
    }

    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function estaEmAberto(): bool
    {
        return is_null($this->data_devolucao_real);
    }

    public function estaAtrasado(): bool
    {
        return $this->estaEmAberto() && now()->gt($this->data_devolucao_prevista);
    }
}
