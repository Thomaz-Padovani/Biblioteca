<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = ['livro_id', 'usuario_id', 'data_reserva', 'status'];

    protected function casts(): array
    {
        return ['data_reserva' => 'date'];
    }

    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }
}
