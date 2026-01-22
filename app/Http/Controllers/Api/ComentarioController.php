<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comentario;
use App\Models\Receta;
use App\Http\Resources\ComentarioResource;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    /**
     * Añade un comentario a una receta (usuario autenticado).
     */
    public function store(Request $request, Receta $receta)
    {
        $user = $request->user();

        $data = $request->validate([
            'texto' => 'required|string',
        ]);

        $comentario = Comentario::create([
            'receta_id' => $receta->id,
            'user_id' => $user->id,
            'texto' => $data['texto'],
        ]);

        return response()->json(new ComentarioResource($comentario), 201);
    }

    /**
     * Borra un comentario (autor o admin).
     */
    public function destroy(Comentario $comentario, Request $request)
    {
        $this->authorize('delete', $comentario);

        $comentario->delete();

        return response()->json(['message' => 'Comentario eliminado']);
    }
}
