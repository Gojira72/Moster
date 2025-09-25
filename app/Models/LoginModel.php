<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class LoginModel extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;

    /**
     * Nome da tabela no banco.
     */
    protected $table = 'tb_usuarios';

    /**
     * Campos permitidos para atribuição em massa.
     */
    protected $fillable = [
        'nomeUsuario',
        'emailUsuario',
        'senhaUsuario',
        'telefone',
        'avatar_url',
        'documento',
        'remember_token',
    ];

    /**
     * Campos ocultos nas serializações.
     */
    protected $hidden = [
        'senhaUsuario',
        'remember_token',
    ];

    /**
     * Conversões automáticas de atributos.
     */
    protected $casts = [
        'emailUsuario' => 'string',
        'telefone' => 'string',
    ];

    /**
     * Retorna o atributo utilizado como senha pela autenticação do Laravel.
     */
    public function getAuthPassword()
    {
        return $this->senhaUsuario;
    }

    /**
     * Define explicitamente o nome do campo utilizado como identificador.
     */
    public function getAuthIdentifierName()
    {
        return 'emailUsuario';
    }

    /**
     * Impede que o Laravel procure pelo campo remember_token quando ele não existir.
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Conta vinculada ao usuário.
     */
    public function conta(): HasOne
    {
        return $this->hasOne(Conta::class, 'usuario_id');
    }

    /**
     * Cartões vinculados ao usuário.
     */
    public function cartoes(): HasMany
    {
        return $this->hasMany(Cartao::class, 'usuario_id');
    }

    /**
     * Transações do usuário através da conta.
     */
    public function transacoes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Transacao::class,
            Conta::class,
            'usuario_id',
            'conta_id'
        )->latest('ocorreu_em');
    }
}
