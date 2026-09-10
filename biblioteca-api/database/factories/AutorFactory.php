<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AutorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'biografia' => fake()->paragraph(),
        ];
    }
}
