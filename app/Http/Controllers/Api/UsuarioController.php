<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsuarioResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'dados' => new UsuarioResource($request->user()),
        ]);
    }
}
