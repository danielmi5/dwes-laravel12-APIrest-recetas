<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Receta;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Alterna el "like" del usuario autenticado sobre la receta.
     */
    public function toggle(Receta $receta, Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $exists = $receta->likes()->where('user_id', $user->id)->exists();

        if ($exists) {
            $receta->likes()->detach($user->id);
            $has = false;
        } else {
            $receta->likes()->attach($user->id);
            $has = true;
        }

        $likesCount = $receta->likes()->count();

        return response()->json(['liked' => $has, 'likes_count' => $likesCount]);
    }
}
