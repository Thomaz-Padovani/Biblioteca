<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // POST /api/register
    public function register(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario = Usuario::create([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
            'perfil' => 'usuario',
        ]);

        $token = $usuario->createToken('biblioteca')->plainTextToken;

        return response()->json(['usuario' => $usuario, 'token' => $token], 201);
    }

    // POST /api/login
    public function login(Request $request)
    {
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credenciais)) {
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        $usuario = Usuario::where('email', $credenciais['email'])->firstOrFail();
        $token = $usuario->createToken('biblioteca')->plainTextToken;

        return response()->json(['usuario' => $usuario, 'token' => $token]);
    }

    // POST /api/logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }

    // GET /api/me
    public function me(Request $request)
    {
        return $request->user();
    }
}
