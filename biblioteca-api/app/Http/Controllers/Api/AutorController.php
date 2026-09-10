<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    public function index()
    {
        return Autor::withCount('livros')->orderBy('nome')->paginate(15);
    }

    public function show(Autor $autor)
    {
        return $autor->load('livros');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'biografia' => ['nullable', 'string'],
        ]);

        return response()->json(Autor::create($dados), 201);
    }

    public function update(Request $request, Autor $autor)
    {
        $dados = $request->validate([
            'nome' => ['sometimes', 'required', 'string', 'max:255'],
            'biografia' => ['nullable', 'string'],
        ]);

        $autor->update($dados);

        return $autor;
    }

    public function destroy(Autor $autor)
    {
        $autor->delete();

        return response()->noContent();
    }
}
