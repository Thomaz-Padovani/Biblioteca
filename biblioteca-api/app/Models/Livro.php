<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livro extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'isbn', 'ano_publicacao', 'capa_path', 'capa_url', 'autor_id'];

    public function autor(): BelongsTo
    {
        return $this->belongsTo(Autor::class);
    }

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class);
    }

    public function emprestimos(): HasMany
    {
        return $this->hasMany(Emprestimo::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    // Scope local: só livros sem nenhum empréstimo em aberto
    public function scopeDisponiveis($query)
    {
        return $query->whereDoesntHave('emprestimos', function ($q) {
            $q->whereNull('data_devolucao_real');
        });
    }
}
