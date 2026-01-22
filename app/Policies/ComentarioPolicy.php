<?php

namespace App\Policies;

use App\Models\Comentario;
use App\Models\User;

class ComentarioPolicy
{
    /**
     * Determina si el usuario puede crear comentarios.
     */
    public function create(?User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determina si el usuario puede eliminar el comentario.
     */
    public function delete(User $user, Comentario $comentario): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $comentario->user_id;
    }
}
