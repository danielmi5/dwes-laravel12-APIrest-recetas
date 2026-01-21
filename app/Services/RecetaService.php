<?php

namespace App\Services;

use App\Models\Receta;
use DomainException;

/**
 * Class RecetaService
 *
 * Servicio con reglas de negocio relacionadas con las recetas.
 *
 * @package App\Services
 */
class RecetaService
{
    
    /**
     * Comprueba si una receta puede modificarse según reglas de negocio.
     *
     * @param \App\Models\Receta $receta
     * @throws \DomainException Si la receta está publicada
     * @return void
     */
    public function assertCanBeModified(Receta $receta): void
    {
        if ($receta->publicada) {
            throw new DomainException(
                'No se puede modificar una receta ya publicada',
                0 // No usamos el código numérico de PHP, porque lo mapeamos en el Handler
            );
        }
    }
}
