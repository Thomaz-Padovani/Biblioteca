<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Ficção', 'Técnico', 'Infantil', 'Biografia', 'História'] as $nome) {
            Categoria::firstOrCreate(['nome' => $nome]);
        }
    }
}
