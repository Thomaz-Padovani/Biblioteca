<?php

namespace Tests\Feature;

use App\Models\Livro;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmprestimoTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_pode_emprestar_um_livro_disponivel(): void
    {
        $usuario = Usuario::factory()->create();
        $livro = Livro::factory()->create();

        $response = $this->actingAs($usuario, 'sanctum')->postJson("/api/v1/livros/{$livro->id}/emprestimos");

        $response->assertCreated();
        $this->assertDatabaseHas('emprestimos', [
            'livro_id' => $livro->id,
            'usuario_id' => $usuario->id,
            'data_devolucao_real' => null,
        ]);
    }

    public function test_nao_permite_emprestar_livro_ja_emprestado(): void
    {
        $livro = Livro::factory()->create();
        $primeiroUsuario = Usuario::factory()->create();
        $segundoUsuario = Usuario::factory()->create();

        $this->actingAs($primeiroUsuario, 'sanctum')->postJson("/api/v1/livros/{$livro->id}/emprestimos");

        $response = $this->actingAs($segundoUsuario, 'sanctum')->postJson("/api/v1/livros/{$livro->id}/emprestimos");

        $response->assertUnprocessable();
        $response->assertJson(['erro' => 'Este livro já está emprestado no momento.']);
        $this->assertSame(1, $livro->emprestimos()->count());
    }

    public function test_registrar_devolucao_libera_o_livro_para_novo_emprestimo(): void
    {
        $livro = Livro::factory()->create();
        $usuario = Usuario::factory()->create();

        $emprestimo = $livro->emprestimos()->create([
            'usuario_id' => $usuario->id,
            'data_emprestimo' => now()->subDays(5),
            'data_devolucao_prevista' => now()->addDays(9),
        ]);

        $this->actingAs($usuario, 'sanctum')
            ->patchJson("/api/v1/emprestimos/{$emprestimo->id}/devolucao")
            ->assertOk();

        $this->assertNotNull($emprestimo->fresh()->data_devolucao_real);

        // agora o livro deve aparecer como disponível novamente
        $response = $this->getJson('/api/v1/livros?disponiveis=1');
        $response->assertJsonFragment(['id' => $livro->id]);
    }
}
