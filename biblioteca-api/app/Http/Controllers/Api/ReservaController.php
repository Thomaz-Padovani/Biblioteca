<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livro;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        return Reserva::with(['livro', 'usuario'])
            ->where('usuario_id', $request->user()->id)
            ->orderByDesc('data_reserva')
            ->get();
    }

    public function store(Request $request, Livro $livro)
    {
        $reserva = Reserva::create([
            'livro_id' => $livro->id,
            'usuario_id' => $request->user()->id,
            'data_reserva' => now()->toDateString(),
            'status' => 'ativa',
        ]);

        return response()->json($reserva, 201);
    }

    public function cancelar(Reserva $reserva)
    {
        $reserva->update(['status' => 'cancelada']);

        return $reserva;
    }
}
