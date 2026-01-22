<?php

namespace Tests\Feature;

use App\Models\Receta;
use App\Models\User;
use App\Models\Ingrediente;
use App\Models\Comentario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtensionesTest extends TestCase
{
    use RefreshDatabase;

    public function test_agregar_ingrediente_ok(): void
    {
        $user = User::factory()->create();
        $receta = Receta::factory()->create(['user_id' => $user->id, 'publicada' => false]);

        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/recetas/{$receta->id}/ingredientes", [
                'nombre' => 'Azúcar',
                'cantidad' => '100',
                'unidad' => 'g',
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['nombre' => 'Azúcar']);

        $this->assertDatabaseHas('ingredientes', ['nombre' => 'Azúcar', 'receta_id' => $receta->id]);
    }

    public function test_agregar_ingrediente_en_receta_publicada_falla(): void
    {
        $user = User::factory()->create();
        $receta = Receta::factory()->create(['user_id' => $user->id, 'publicada' => true]);

        $token = $user->createToken('api')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/recetas/{$receta->id}/ingredientes", [
                'nombre' => 'Sal',
            ]);

        $response->assertStatus(409);
    }

    public function test_dar_y_quitar_like(): void
    {
        $user = User::factory()->create();
        $receta = Receta::factory()->create();

        $token = $user->createToken('api')->plainTextToken;

        $res1 = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/recetas/{$receta->id}/like");

        $res1->assertStatus(200)->assertJson(['liked' => true]);

        $res2 = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/recetas/{$receta->id}/like");

        $res2->assertStatus(200)->assertJson(['liked' => false]);
    }

    public function test_comentar_y_borrar_propio_comentario(): void
    {
        $user = User::factory()->create();
        $receta = Receta::factory()->create();

        $token = $user->createToken('api')->plainTextToken;

        $res = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson("/api/recetas/{$receta->id}/comentarios", ['texto' => 'Buenísima']);

        $res->assertStatus(201)->assertJsonFragment(['texto' => 'Buenísima']);

        $id = $res->json('id');

        $del = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson("/api/comentarios/{$id}");

        $del->assertStatus(200);
    }

    public function test_no_puede_borrar_comentario_ajeno(): void
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $receta = Receta::factory()->create();

        $tokenAuthor = $author->createToken('api')->plainTextToken;
        $res = $this->withHeader('Authorization', 'Bearer '.$tokenAuthor)
            ->postJson("/api/recetas/{$receta->id}/comentarios", ['texto' => 'Comentario']);

        $id = $res->json('id');

        $tokenOther = $other->createToken('api')->plainTextToken;
        $del = $this->withHeader('Authorization', 'Bearer '.$tokenOther)
            ->deleteJson("/api/comentarios/{$id}");

        $del->assertStatus(403);
    }
}
