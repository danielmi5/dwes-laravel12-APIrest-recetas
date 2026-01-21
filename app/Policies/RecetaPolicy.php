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
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    /**
     * Determine whether the user can view the model.
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
     * Determine whether the user can create models.
     */
    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    /**
     * Determine whether the user can update the model.
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
     * Determine whether the user can delete the model.
     */
    /**
     * Determine whether the user can delete the model.
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
     * Determine whether the user can restore the model.
     */
    /**
     * Determine whether the user can restore the model.
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
     * Determine whether the user can permanently delete the model.
     */
    /**
     * Determine whether the user can permanently delete the model.
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
