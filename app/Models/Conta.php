<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conta extends Model
{
    use HasFactory;

    protected $table = 'contas';

    protected $fillable = [
        'usuario_id',
        'saldo_atual',
        'limite_credito',
        'limite_disponivel',
    ];

    protected $casts = [
        'saldo_atual' => 'decimal:2',
        'limite_credito' => 'decimal:2',
        'limite_disponivel' => 'decimal:2',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(LoginModel::class, 'usuario_id');
    }

    public function transacoes(): HasMany
    {
        return $this->hasMany(Transacao::class, 'conta_id')->orderByDesc('ocorreu_em');
    }
}
