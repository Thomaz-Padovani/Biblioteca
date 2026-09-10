<?php

namespace Tests\Feature;

use App\Models\Autor;
use App\Models\Livro;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroTest extends TestCase
{
    use RefreshDatabase;

    public function test_qualquer_pessoa_pode_listar_livros(): void
    {
        Livro::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/livros');

        $response->assertOk()->assertJsonCount(3, 'data');
    }

    public function test_usuario_comum_nao_pode_cadastrar_livro(): void
    {
        $usuario = Usuario::factory()->create(); // perfil padrão: "usuario"
        $autor = Autor::factory()->create();

        $response = $this->actingAs($usuario, 'sanctum')->postJson('/api/v1/livros', [
            'titulo' => 'Clean Code',
            'isbn' => '9780132350884',
            'autor_id' => $autor->id,
        ]);

        $response->assertForbidden();
    }

    public function test_bibliotecario_pode_cadastrar_livro(): void
    {
        $bibliotecario = Usuario::factory()->bibliotecario()->create();
        $autor = Autor::factory()->create();

        $response = $this->actingAs($bibliotecario, 'sanctum')->postJson('/api/v1/livros', [
            'titulo' => 'Clean Code',
            'isbn' => '9780132350884',
            'autor_id' => $autor->id,
        ]);

        $response->assertCreated()->assertJsonPath('titulo', 'Clean Code');
        $this->assertDatabaseHas('livros', ['isbn' => '9780132350884']);
    }

    public function test_nao_permite_isbn_duplicado(): void
    {
        $bibliotecario = Usuario::factory()->bibliotecario()->create();
        $autor = Autor::factory()->create();
        Livro::factory()->create(['isbn' => '9780132350884']);

        $response = $this->actingAs($bibliotecario, 'sanctum')->postJson('/api/v1/livros', [
            'titulo' => 'Outro Título',
            'isbn' => '9780132350884',
            'autor_id' => $autor->id,
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('isbn');
    }

    public function test_nao_permite_remover_livro_com_emprestimo_em_aberto(): void
    {
        $bibliotecario = Usuario::factory()->bibliotecario()->create();
        $livro = Livro::factory()->create();
        $livro->emprestimos()->create([
            'usuario_id' => Usuario::factory()->create()->id,
            'data_emprestimo' => now(),
            'data_devolucao_prevista' => now()->addDays(14),
        ]);

        $response = $this->actingAs($bibliotecario, 'sanctum')->deleteJson("/api/v1/livros/{$livro->id}");

        $response->assertUnprocessable();
        $this->assertDatabaseHas('livros', ['id' => $livro->id]);
    }
}
