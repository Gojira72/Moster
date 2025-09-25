<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartaoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartaoController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $cartao = $request->user()->cartoes()->latest('updated_at')->first();

        if (! $cartao) {
            return response()->json([
                'mensagem' => 'Nenhum cartão cadastrado para o usuário.',
            ], 404);
        }

        return response()->json([
            'dados' => new CartaoResource($cartao),
        ]);
    }
}
