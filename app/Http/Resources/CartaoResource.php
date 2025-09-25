<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Cartao */
class CartaoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'apelido' => $this->apelido,
            'bandeira' => $this->bandeira,
            'ultimosDigitos' => $this->ultimos_digitos,
            'limiteTotal' => (float) $this->limite_total,
            'limiteDisponivel' => (float) $this->limite_disponivel,
            'status' => $this->status,
            'vencimentoFatura' => optional($this->vencimento_fatura)?->toDateString(),
            'atualizadoEm' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}
