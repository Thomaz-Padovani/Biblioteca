<?php

namespace Database\Seeders;

use App\Models\Livro;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategoriaSeeder::class);

        Usuario::factory()->bibliotecario()->create([
            'nome' => 'Bibliotecária Chefe',
            'email' => 'bibliotecaria@biblioteca.test',
        ]);

        Usuario::factory()->count(10)->create();

        Livro::factory()->count(30)->create()->each(function (Livro $livro) {
            $livro->categorias()->attach(
                \App\Models\Categoria::inRandomOrder()->limit(rand(1, 2))->pluck('id')
            );
        });
    }
}
