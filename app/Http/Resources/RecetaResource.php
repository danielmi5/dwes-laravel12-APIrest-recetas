<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RecetaResource
 *
 * Recurso API que serializa una `Receta`.
 *
 * @package App\Http\Resources
 */
class RecetaResource extends JsonResource
{
    
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'instrucciones' => $this->instrucciones,
            'publicada' => $this->publicada,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ];
    }
}
