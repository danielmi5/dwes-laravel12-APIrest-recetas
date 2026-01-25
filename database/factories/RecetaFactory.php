<?php

namespace Database\Factories;

use App\Models\Receta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receta>
 */
/**
 * Class RecetaFactory
 *
 * Factory que genera instancias de `Receta` en tests y seeders.
 */
class RecetaFactory extends Factory
{
    
    /**
     * The model that this factory creates.
     *
     * @var string
     */
    protected $model = Receta::class;

    /**
     * Definición por defecto de la fábrica.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'titulo' => $this->faker->sentence(4),
            'descripcion' => $this->faker->sentence(10),
            'instrucciones' => $this->faker->paragraph(4),
        ];
    }
}
