<?php

namespace Tests\Feature;

use App\Models\Autor;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidacaoAvancadaTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejeita_isbn_com_formato_invalido(): void
    {
        $bibliotecario = Usuario::factory()->bibliotecario()->create();
        $autor = Autor::factory()->create();

        $response = $this->actingAs($bibliotecario, 'sanctum')->postJson('/api/v1/livros', [
            'titulo' => 'Título qualquer',
            'isbn' => '123', // formato inválido, não passa pela IsbnValido
            'autor_id' => $autor->id,
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('isbn');
    }

    public function test_aceita_isbn_13_valido(): void
    {
        $bibliotecario = Usuario::factory()->bibliotecario()->create();
        $autor = Autor::factory()->create();

        $response = $this->actingAs($bibliotecario, 'sanctum')->postJson('/api/v1/livros', [
            'titulo' => 'Clean Code',
            'isbn' => '9780132350884',
            'autor_id' => $autor->id,
        ]);

        $response->assertCreated();
    }
}
