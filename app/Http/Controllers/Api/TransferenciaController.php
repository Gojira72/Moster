<?php

namespace App\Http\Controllers\Api;

use App\Enums\TipoTransacao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransferenciaRequest;
use App\Http\Resources\ContaResource;
use App\Http\Resources\TransacaoResource;
use App\Models\Transacao;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransferenciaController extends Controller
{
    public function store(TransferenciaRequest $request): JsonResponse
    {
        $conta = $request->user()->conta;

        if (! $conta) {
            return response()->json([
                'mensagem' => 'Conta não localizada para o usuário autenticado.',
            ], 404);
        }

        $dados = $request->validated();

        if ($conta->saldo_atual < $dados['valor']) {
            return response()->json([
                'mensagem' => 'Saldo insuficiente para concluir a transferência.',
            ], 422);
        }

        $transacao = DB::transaction(function () use ($conta, $dados) {
            $conta->decrement('saldo_atual', $dados['valor']);

            return Transacao::create([
                'conta_id' => $conta->id,
                'tipo' => TipoTransacao::Transferencia,
                'categoria' => 'transferencia',
                'valor' => $dados['valor'],
                'descricao' => $dados['descricao'],
                'contraparte' => $dados['destinatario'],
                'ocorreu_em' => now(),
            ]);
        });

        $conta->refresh();

        return response()->json([
            'mensagem' => 'Transferência registrada com sucesso.',
            'dados' => [
                'conta' => new ContaResource($conta),
                'transacao' => new TransacaoResource($transacao),
            ],
        ], 201);
    }
}
