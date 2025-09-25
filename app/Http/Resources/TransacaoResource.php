<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Transacao */
class TransacaoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo->value,
            'categoria' => $this->categoria,
            'valor' => (float) $this->valor,
            'descricao' => $this->descricao,
            'contraparte' => $this->contraparte,
            'ocorreuEm' => optional($this->ocorreu_em)?->toIso8601String(),
        ];
    }
}
