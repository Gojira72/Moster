<?php

namespace App\Models;

use App\Enums\TipoTransacao;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacoes';

    protected $fillable = [
        'conta_id',
        'tipo',
        'categoria',
        'valor',
        'descricao',
        'contraparte',
        'ocorreu_em',
    ];

    protected $casts = [
        'tipo' => TipoTransacao::class,
        'valor' => 'decimal:2',
        'ocorreu_em' => 'datetime',
    ];

    public function conta(): BelongsTo
    {
        return $this->belongsTo(Conta::class, 'conta_id');
    }
}
