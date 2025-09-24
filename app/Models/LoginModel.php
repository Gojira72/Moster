<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class LoginModel extends Model implements Authenticatable
{
    use AuthenticatableTrait;

    // Nome da tabela no banco
    protected $table = 'tb_usuarios';

    // Campos que podem ser preenchidos via mass-assignment
    protected $fillable = [
        'nomeUsuario',
        'emailUsuario',
        'senhaUsuario',
    ];

    // Campos que não devem ser expostos
    protected $hidden = [
        'senhaUsuario',
    ];

    // Desativa timestamps se sua tabela não tiver created_at/updated_at
    public $timestamps = false;

    // Retorna a senha para autenticação
    public function getAuthPassword()
    {
        return $this->senhaUsuario;
    }

    // Retorna o campo usado como identificador (normalmente email)
    public function getAuthIdentifierName()
    {
        return 'emailUsuario';
    }
}

