<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = ['nome', 'email', 'password', 'perfil'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function emprestimos(): HasMany
    {
        return $this->hasMany(Emprestimo::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    public function ehBibliotecario(): bool
    {
        return $this->perfil === 'bibliotecario';
    }
}
