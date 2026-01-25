<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\IngredienteResource;
use App\Http\Resources\ComentarioResource;
use Illuminate\Support\Facades\Storage;

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
     * Convierte el recurso en un array.
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
            'ingredientes' => IngredienteResource::collection($this->whenLoaded('ingredientes')),
            'likes_count' => $this->when(isset($this->likes_count), $this->likes_count),
            'comentarios' => ComentarioResource::collection($this->whenLoaded('comentarios')),
            'imagen_url' => $this->when($this->imagen, Storage::url($this->imagen)),
        ];
    }
}
