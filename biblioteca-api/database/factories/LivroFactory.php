<?php

namespace Database\Factories;

use App\Models\Autor;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivroFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(3),
            'isbn' => fake()->unique()->isbn13(),
            'ano_publicacao' => fake()->year(),
            'autor_id' => Autor::factory(),
        ];
    }
}
