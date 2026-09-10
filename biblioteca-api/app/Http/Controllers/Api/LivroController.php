<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use App\Http\Requests\UploadCapaRequest;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class LivroController extends Controller
{
    // GET /api/livros
    public function index(Request $request)
    {
        $query = Livro::with(['autor', 'categorias']);

        if ($request->boolean('disponiveis')) {
            $query->disponiveis();
        }

        if ($busca = $request->query('busca')) {
            $query->where('titulo', 'like', "%{$busca}%");
        }

        // whitelist simples: evita passar qualquer string do usuário direto pro orderBy
        $ordenar = in_array($request->query('ordenar'), ['titulo', 'ano_publicacao'], true)
            ? $request->query('ordenar')
            : 'titulo';

        return $query->orderBy($ordenar)->paginate(15);
    }

    // GET /api/livros/{livro}
    public function show(Livro $livro)
    {
        return $livro->load(['autor', 'categorias', 'emprestimos' => function ($q) {
            $q->whereNull('data_devolucao_real');
        }]);
    }

    // POST /api/livros
    public function store(StoreLivroRequest $request)
    {
        $livro = Livro::create($request->validated());

        if ($request->has('categorias')) {
            $livro->categorias()->sync($request->input('categorias'));
        }

        return response()->json($livro->load(['autor', 'categorias']), 201);
    }

    // PUT/PATCH /api/livros/{livro}
    public function update(UpdateLivroRequest $request, Livro $livro)
    {
        $livro->update($request->validated());

        if ($request->has('categorias')) {
            $livro->categorias()->sync($request->input('categorias'));
        }

        return $livro->load(['autor', 'categorias']);
    }

    // DELETE /api/livros/{livro}
    public function destroy(Livro $livro)
    {
        if ($livro->emprestimos()->whereNull('data_devolucao_real')->exists()) {
            return response()->json([
                'message' => 'Não é possível remover um livro com empréstimo em aberto.',
            ], 422);
        }

        if ($livro->capa_path) {
            Storage::disk('public')->delete($livro->capa_path);
        }

        $livro->delete();

        return response()->noContent();
    }

    // POST /api/livros/{livro}/capa
    public function uploadCapa(UploadCapaRequest $request, Livro $livro)
    {
        // remove a capa antiga, se existir, para não acumular lixo no disco
        if ($livro->capa_path) {
            Storage::disk('public')->delete($livro->capa_path);
        }

        // redimensiona para uma largura máxima consistente antes de salvar
        $imagem = Image::read($request->file('capa'))
            ->scaleDown(width: 600)
            ->toWebp(quality: 80);

        $nomeArquivo = 'capas/' . Str::uuid() . '.webp';
        Storage::disk('public')->put($nomeArquivo, (string) $imagem);

        $livro->update([
            'capa_path' => $nomeArquivo,
            'capa_url' => Storage::disk('public')->url($nomeArquivo),
        ]);

        return $livro->fresh();
    }

    // DELETE /api/livros/{livro}/capa
    public function removerCapa(Livro $livro)
    {
        if ($livro->capa_path) {
            Storage::disk('public')->delete($livro->capa_path);
            $livro->update(['capa_path' => null, 'capa_url' => null]);
        }

        return $livro->fresh();
    }
}
