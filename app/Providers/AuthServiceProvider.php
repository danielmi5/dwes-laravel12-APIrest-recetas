<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Receta;
use App\Models\Comentario;
use App\Policies\RecetaPolicy;
use App\Policies\ComentarioPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
        * Mapeo de políticas para la aplicación.
        *
        * @var array<class-string, class-string>
     */
    protected $policies = [
        Receta::class => RecetaPolicy::class,
        Comentario::class => ComentarioPolicy::class,
    ];

    /**
     * Registra los servicios de autenticación / autorización.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
