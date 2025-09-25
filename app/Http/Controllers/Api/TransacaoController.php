<?php

namespace App\Http\Controllers\Api;

use App\Enums\TipoTransacao;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransacaoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransacaoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $conta = $request->user()->conta;

        if (! $conta) {
            return response()->json([
                'mensagem' => 'Conta não localizada para o usuário autenticado.',
            ], 404);
        }

        $consulta = $conta->transacoes()->getQuery();

        if ($tipo = $request->query('tipo')) {
            $tipoEnum = collect(TipoTransacao::cases())->firstWhere('value', $tipo);
            if ($tipoEnum) {
                $consulta->where('tipo', $tipoEnum->value);
            }
        }

        if ($inicio = $request->query('data_inicio')) {
            $consulta->whereDate('ocorreu_em', '>=', $inicio);
        }

        if ($fim = $request->query('data_fim')) {
            $consulta->whereDate('ocorreu_em', '<=', $fim);
        }

        $porPagina = (int) $request->query('por_pagina', 20);
        $porPagina = max(1, min(50, $porPagina));

        $transacoes = $consulta->orderByDesc('ocorreu_em')->paginate($porPagina);

        return TransacaoResource::collection($transacoes)
            ->additional([
                'meta' => [
                    'paginaAtual' => $transacoes->currentPage(),
                    'totalPaginas' => $transacoes->lastPage(),
                    'totalRegistros' => $transacoes->total(),
                ],
            ])->response();
    }
}
