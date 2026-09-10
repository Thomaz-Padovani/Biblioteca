<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        return Categoria::orderBy('nome')->get();
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:categorias,nome'],
        ]);

        return response()->json(Categoria::create($dados), 201);
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return response()->noContent();
    }
}
