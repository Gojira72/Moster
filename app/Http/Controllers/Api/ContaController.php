<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContaController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $conta = $request->user()->conta;

        if (! $conta) {
            return response()->json([
                'mensagem' => 'Conta não localizada para o usuário autenticado.',
            ], 404);
        }

        return response()->json([
            'dados' => new ContaResource($conta),
        ]);
    }
}
