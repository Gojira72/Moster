<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\LoginModel */
class UsuarioResource extends JsonResource
{
    /**
     * Transforma o recurso em array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nomeUsuario,
            'email' => $this->emailUsuario,
            'telefone' => $this->telefone,
            'avatarUrl' => $this->avatar_url,
            'documento' => $this->documento,
            'criadoEm' => optional($this->created_at)?->toIso8601String(),
            'atualizadoEm' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}
