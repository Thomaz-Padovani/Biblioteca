<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UsuarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'perfil' => 'usuario',
        ];
    }

    public function bibliotecario(): static
    {
        return $this->state(fn () => ['perfil' => 'bibliotecario']);
    }
}
