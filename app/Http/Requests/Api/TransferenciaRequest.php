<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TransferenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'valor' => ['required', 'numeric', 'min:1'],
            'descricao' => ['required', 'string', 'max:120'],
            'destinatario' => ['required', 'string', 'max:120'],
        ];
    }
}
