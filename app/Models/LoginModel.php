<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LoginModel extends Authenticatable
{
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
}

