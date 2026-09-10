<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function livros(): BelongsToMany
    {
        return $this->belongsToMany(Livro::class);
    }
}
