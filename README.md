# Extensión API REST - Recetas

Resumen breve del trabajo añadido al proyecto base (Laravel 12 - API REST).

## Qué se ha implementado

### Partes obligatorias
  - Modelo Ingredientes:
    - Modelo `Ingrediente` y migración `ingredientes` (campos: `nombre`, `cantidad`, `unidad`, `receta_id`).
    - Endpoints:
      - `POST /api/recetas/{receta}/ingredientes` — Añadir ingrediente (controller: `IngredienteController@store`).
      - `DELETE /api/ingredientes/{ingrediente}` — Borrar ingrediente (controller: `IngredienteController@destroy`).
    - Autorización mediante `RecetaPolicy::manageIngredients` (propietario o `admin`).
    - `RecetaService::assertCanBeModified($receta)` invocado antes de crear/borrar para aplicar la regla de negocio (no modificar si la receta está publicada).

- Implementado Likes:
  - Migración `likes` (user_id, receta_id) con restricción única `user_id + receta_id`.
  - Endpoint `POST /api/recetas/{receta}/like` — Toggle like (controller: `LikeController@toggle`).
  - Respuesta JSON: `{'liked': bool, 'likes_count': int}`.

- Módulo de Comentarios:
  - Modelo `Comentario` y migración `comentarios` (`texto`, `user_id`, `receta_id`).
  - Endpoints:
    - `POST /api/recetas/{receta}/comentarios` — Añadir comentario (autenticado, controller: `ComentarioController@store`).
    - `DELETE /api/comentarios/{comentario}` — Borrar comentario (controller: `ComentarioController@destroy`).
  - Policy `ComentarioPolicy`: `create` permitido si autenticado; `delete` permitido si autor o `admin`.

- Recursos y serialización:
  - `RecetaResource` actualizado para incluir `ingredientes`, `comentarios` y `likes_count`.
  - `IngredienteResource` y `ComentarioResource` añadidos.

- Tests:
  - `tests/Feature/ExtensionesTest.php` con casos para ingredientes, likes y comentarios.

### Partes opcionales



## Cómo probar la API

1. Instalar dependencias y migrar la BD:

```bash
composer install
cp .env.example .env
# Se debe configurar .env (DB, etc.)
php artisan key:generate
php artisan migrate
```

2. Ejecutar la aplicación (Sail o `php -S` según tu entorno):

```bash
php artisan serve --port=8000
```

3. Autenticarse y obtener token (HTTPie):

```bash
http POST :8000/api/auth/login email=admin@demo.local password=password
export TOKEN=eyJ0eXAiOiJKV1Qi...  # Se debe sustituir por el token real
```

4. Comandos de ejemplo (HTTPie):

Añadir ingrediente:
```bash
http POST :8000/api/recetas/1/ingredientes \
  "Authorization:Bearer $TOKEN" \
  nombre="Huevo" cantidad=3 unidad="ud"
```

Borrar ingrediente:
```bash
http DELETE :8000/api/ingredientes/5 "Authorization:Bearer $TOKEN"
```

Dar/quitar like (toggle):
```bash
http POST :8000/api/recetas/1/like "Authorization:Bearer $TOKEN"
```

Comentar receta:
```bash
http POST :8000/api/recetas/1/comentarios \
  "Authorization:Bearer $TOKEN" texto="Muy buena receta"
```

Borrar comentario:
```bash
http DELETE :8000/api/comentarios/3 "Authorization:Bearer $TOKEN"
```

5. Ejecutar tests:

```bash
# Ejecuta la suite de tests (requiere entorno de pruebas configurado)
php artisan test --filter ExtensionesTest
```

## Decisiones técnicas

- Relaciones en base de datos:
  - `Receta` 1:N `Ingrediente` (cada ingrediente pertenece a una receta).
  - `Receta` 1:N `Comentario`.
  - `Receta` N:M `User` vía tabla `likes` (pivot sin modelo explícito).

- Diseño de endpoints y controllers:
  - Se mantienen patrones RESTful y el uso de Controllers por recurso (`IngredienteController`, `LikeController`, `ComentarioController`).
  - Se usan API Resources para la serialización coherente de respuestas.

- Autorización y validación:
  - Policies para autorización: `RecetaPolicy::manageIngredients` (owner o admin) y `ComentarioPolicy`.
  - Validación con `$request->validate()` en controllers, siguiendo el estilo del proyecto.
  - `RecetaService::assertCanBeModified($receta)` invocado en `IngredienteController` para garantizar la regla de negocio: no modificar recetas publicadas.

- Manejo de likes:
  - Toggle simple: si existe registro se elimina, si no existe se crea.
  - Restricción única en BD evita duplicados (índice único `user_id + receta_id`).

## Dificultades encontradas

- Arrancar la aplicación: me ha costado saber arrancar la aplicación.

- Testing: no he conseguido ejecutar los tests correctamente.