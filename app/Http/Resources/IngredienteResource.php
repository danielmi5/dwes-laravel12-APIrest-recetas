<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredienteResource extends JsonResource
{
    /**
     * Convierte el recurso en un array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'cantidad' => $this->cantidad,
            'unidad' => $this->unidad,
            'receta_id' => $this->receta_id,
        ];
    }
}
