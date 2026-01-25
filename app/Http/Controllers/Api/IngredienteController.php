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
     * Lista los ingredientes de una receta.
     */
    public function index(Receta $receta)
    {
        return IngredienteResource::collection($receta->ingredientes);
    }

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

        $ingrediente = $receta->ingredientes()->create($data);

        return response()->json(new IngredienteResource($ingrediente), 201);
    }

    /**
     * Actualiza un ingrediente.
     */
    public function update(Request $request, Ingrediente $ingrediente, RecetaService $recetaService)
    {
        $receta = $ingrediente->receta;

        $this->authorize('manageIngredients', $receta);

        $recetaService->assertCanBeModified($receta);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'cantidad' => 'nullable|string|max:50',
            'unidad' => 'nullable|string|max:50',
        ]);

        $ingrediente->update($data);

        return response()->json(new IngredienteResource($ingrediente));
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
