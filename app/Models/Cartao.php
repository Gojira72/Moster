<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cartao extends Model
{
    use HasFactory;

    protected $table = 'cartoes';

    protected $fillable = [
        'usuario_id',
        'apelido',
        'bandeira',
        'ultimos_digitos',
        'limite_total',
        'limite_disponivel',
        'status',
        'vencimento_fatura',
    ];

    protected $casts = [
        'limite_total' => 'decimal:2',
        'limite_disponivel' => 'decimal:2',
        'vencimento_fatura' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(LoginModel::class, 'usuario_id');
    }
}
