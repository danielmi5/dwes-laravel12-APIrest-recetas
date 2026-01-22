<?php

namespace App\Policies;

use App\Models\Receta;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Class RecetaPolicy
 *
 * Política de autorización para las operaciones sobre `Receta`.
 *
 * @package App\Policies
 */
class RecetaPolicy
{
    
    /**
     * Determina si el usuario puede ver cualquier modelo.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determina si el usuario puede ver el modelo.
     */
    /**
     * Determina si el usuario puede ver el modelo.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Receta $receta
     * @return bool
     */
    public function view(User $user, Receta $receta): bool
    {
        return false;
    }

    /**
     * Determina si el usuario puede crear modelos.
     */
    /**
     * Determina si el usuario puede crear modelos.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determina si el usuario puede actualizar el modelo.
     */
    /**
     * Determina si el usuario puede actualizar el modelo.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Receta $receta
     * @return bool
     */
    public function update(User $user, Receta $receta): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        // Solo el dueño puede modificar su receta, además del admin.
        return $user->id === $receta->user_id;
    }

    /**
     * Determina si el usuario puede gestionar los ingredientes de la receta.
     * Alias de update (propietario o admin).
     *
     * @param \App\Models\User $user
     * @param \App\Models\Receta $receta
     * @return bool
     */
    public function manageIngredients(User $user, Receta $receta): bool
    {
        return $this->update($user, $receta);
    }

    /**
     * Determina si el usuario puede eliminar el modelo.
     */
    /**
     * Determina si el usuario puede eliminar el modelo.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Receta $receta
     * @return bool
     */
    public function delete(User $user, Receta $receta): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        // Solo el dueño puede borrar su receta, además del admin.
        return $user->id === $receta->user_id;
    }

    /**
     * Determina si el usuario puede restaurar el modelo.
     */
    /**
     * Determina si el usuario puede restaurar el modelo.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Receta $receta
     * @return bool
     */
    public function restore(User $user, Receta $receta): bool
    {
        return false;
    }

    /**
     * Determina si el usuario puede eliminar permanentemente el modelo.
     */
    /**
     * Determina si el usuario puede eliminar permanentemente el modelo.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Receta $receta
     * @return bool
     */
    public function forceDelete(User $user, Receta $receta): bool
    {
        return false;
    }
}
