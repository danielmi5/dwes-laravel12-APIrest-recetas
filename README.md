# Extensión API REST - Recetas

Resumen breve del trabajo añadido al proyecto base (Laravel 12 - API REST).

## Qué se ha implementado

### Partes obligatorias

#### Ingredientes
    
- Modelo `Ingrediente` y migración `ingredientes` (campos: `nombre`, `cantidad`, `unidad`, `receta_id`).
- Endpoints:
  - `GET /api/recetas/{receta}/ingredientes` — Listar ingredientes de una receta (controller: `IngredienteController@index`).
  - `POST /api/recetas/{receta}/ingredientes` — Añadir ingrediente (controller: `IngredienteController@store`).
  - `PUT /api/ingredientes/{ingrediente}` — Modificar ingrediente (controller: `IngredienteController@update`).
  - `DELETE /api/ingredientes/{ingrediente}` — Borrar ingrediente (controller: `IngredienteController@destroy`).
- Autorización mediante `RecetaPolicy::manageIngredients` (propietario o `admin`).
- `RecetaService::assertCanBeModified($receta)` invocado antes de crear/editar/borrar para aplicar la regla de negocio.

#### Likes

- Migración `likes` (user_id, receta_id) con restricción única `user_id + receta_id`.
- Endpoint `POST /api/recetas/{receta}/like` — Toggle like (controller: `LikeController@toggle`).
- Respuesta JSON: `{'liked': bool, 'likes_count': int}`.

#### Comentarios

  - Modelo `Comentario` y migración `comentarios` (`texto`, `user_id`, `receta_id`).
  - Endpoints:
    - `POST /api/recetas/{receta}/comentarios` — Añadir comentario (autenticado, controller: `ComentarioController@store`).
    - `DELETE /api/comentarios/{comentario}` — Borrar comentario (controller: `ComentarioController@destroy`).
  - Policy `ComentarioPolicy`: `create` permitido si autenticado; `delete` permitido si autor o `admin`.

####  Recursos y serialización

  - `RecetaResource` actualizado para incluir `ingredientes`, `comentarios` y `likes_count`.
  - `IngredienteResource` y `ComentarioResource` añadidos.



### Partes semi-opcionales / opcionales

#### Imagen del plato final
- Subida de imagen (JPEG/PNG/WEBP) mediante endpoint:
  - `POST /api/recetas/{receta}/imagen` — sube o actualiza la imagen de la receta.
- Validación implementada: `image|mimes:jpeg,png,webp|max:5120` (acepta hasta 5MB).
- La imagen se guarda en el disco `public` bajo `recetas/` y la URL se expone en el recurso `RecetaResource` como `imagen_url`.
- Si la receta ya tenía imagen previa, se elimina la anterior del storage al subir una nueva.

Ejemplo HTTPie (subida):
```bash
http --form POST :8000/api/recetas/1/imagen \
  "Authorization:Bearer $TOKEN" \
  imagen@/ruta/a/imagen.webp
```

#### Búsquedas avanzadas / filtros 

- Filtros añadidos en `GET /api/recetas`:
  - `?ingredient=nombre` — devuelve recetas que contienen un ingrediente cuyo nombre coincida parcialmente.
  - `?min_likes=N` — filtra recetas con al menos `N` likes.
  - `?sort=-likes_count` — ordenar por número de likes (prefijo `-` para descendente).
  - `?q=texto` — búsqueda por título/descripcion (ya existente, con ILIKE en PostgreSQL).

Ejemplos HTTPie:
```bash
# Buscar por ingrediente
http GET :8000/api/recetas?ingredient=azucar

# Filtrar por mínimo de likes
http GET :8000/api/recetas?min_likes=5

# Ordenar por popularidad (desc)
http GET :8000/api/recetas?sort=-likes_count
```

####  Tests

  - `tests/Feature/ExtensionesTest.php` con casos para ingredientes, likes y comentarios.

## Cómo probar la API

1. Instalar dependencias y migrar la BD:

```bash
composer install
cp .env.example .env
# configurar .env (DB, etc.)
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
export TOKEN=eyJ0eXAiOiJKV1Qi...  # sustituir por el token real
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

Listar ingredientes de una receta:
```bash
http GET :8000/api/recetas/1/ingredientes "Authorization:Bearer $TOKEN"
```

Modificar un ingrediente:
```bash
http PUT :8000/api/ingredientes/5 \
  "Authorization:Bearer $TOKEN" \
  nombre="Huevos" cantidad=6 unidad="ud"
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

- Testing: no he conseguido que un tests funcione correctamente.

- Autorización / Policies: tuve que registrar las policies (`AuthServiceProvider`) para
  que llamadas como `$this->authorize()` resolvieran `ComentarioPolicy` y `RecetaPolicy`.

## Mejoras futuras

1. Documentación con Swagger
2. Implementar redimensionado y múltiples imágenes.
3. Crear más tests.
4. Implementar una interfaz.