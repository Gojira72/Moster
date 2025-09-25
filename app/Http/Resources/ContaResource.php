<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Conta */
class ContaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'saldoAtual' => (float) $this->saldo_atual,
            'limiteCredito' => (float) $this->limite_credito,
            'limiteDisponivel' => (float) $this->limite_disponivel,
            'atualizadoEm' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}
