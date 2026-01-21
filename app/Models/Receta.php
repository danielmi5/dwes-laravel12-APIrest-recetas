<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Receta
 *
 * Modelo que representa una receta de usuario.
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string $descripcion
 * @property string $instrucciones
 * @property bool $publicada
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @package App\Models
 */
class Receta extends Model
{
    
    /** @use HasFactory<\Database\Factories\RecetaFactory> */
    use HasFactory;


    // El atributo protected $fillable sirve para definir qué campos de la tabla
    // pueden ser asignados masivamente (mass assignment). Esto es importante
    // para proteger contra asignaciones no deseadas o maliciosas cuando se crean
    // o actualizan registros en la base de datos.
    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'instrucciones',
        'publicada',
    ];

    // Relación inversa: una receta pertenece a un usuario
    /**
     * Relación: receta pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Relación: una receta tiene muchos ingredientes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredientes()
    {
        return $this->hasMany(\App\Models\Ingrediente::class);
    }

    /**
     * Relación: una receta tiene muchos comentarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comentarios()
    {
        return $this->hasMany(\App\Models\Comentario::class);
    }

    /**
     * Relación: usuarios que han dado like a la receta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany(\App\Models\User::class, 'likes')
            ->withTimestamps();
    }
}
