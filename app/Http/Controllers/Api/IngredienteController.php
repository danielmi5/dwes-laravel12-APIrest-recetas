<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingrediente;
use App\Models\Receta;
use App\Http\Resources\IngredienteResource;
use App\Services\RecetaService;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    /**
     * Añade un ingrediente a una receta.
     */
    public function store(Request $request, Receta $receta, RecetaService $recetaService)
    {
        $this->authorize('manageIngredients', $receta);

        $recetaService->assertCanBeModified($receta);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'cantidad' => 'nullable|string|max:50',
            'unidad' => 'nullable|string|max:50',
        ]);

        $ingrediente = Ingrediente::create(array_merge($data, ['receta_id' => $receta->id]));

        return response()->json(new IngredienteResource($ingrediente), 201);
    }

    /**
     * Borra un ingrediente.
     */
    public function destroy(Ingrediente $ingrediente, RecetaService $recetaService)
    {
        $receta = $ingrediente->receta;

        $this->authorize('manageIngredients', $receta);

        $recetaService->assertCanBeModified($receta);

        $ingrediente->delete();

        return response()->json(['message' => 'Ingrediente eliminado']);
    }
}
