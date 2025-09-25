<?php

namespace Tests\Feature\Api;

use App\Models\Conta;
use App\Models\LoginModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_consegue_autenticar_e_receber_token(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1!')->create([
            'emailUsuario' => 'cliente@exemplo.com',
        ]);
        Conta::factory()->for($usuario, 'usuario')->create();

        $resposta = $this->postJson('/api/auth/login', [
            'email' => 'cliente@exemplo.com',
            'password' => 'SenhaForte1!',
        ]);

        $resposta->assertOk()
            ->assertJsonStructure([
                'mensagem',
                'dados' => [
                    'token',
                    'tipoToken',
                    'expiraEm',
                    'usuario' => [
                        'id',
                        'nome',
                        'email',
                    ],
                ],
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_com_credenciais_invalidas_retorna_erro(): void
    {
        LoginModel::factory()->withPassword('SenhaForte1!')->create([
            'emailUsuario' => 'cliente@exemplo.com',
        ]);

        $resposta = $this->postJson('/api/auth/login', [
            'email' => 'cliente@exemplo.com',
            'password' => 'SenhaErrada',
        ]);

        $resposta->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_logout_revoga_token_atual(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1!')->create([
            'emailUsuario' => 'cliente@exemplo.com',
        ]);
        Conta::factory()->for($usuario, 'usuario')->create();

        $token = $usuario->createToken('mobile');

        $resposta = $this->withToken($token->plainTextToken)->postJson('/api/auth/logout');

        $resposta->assertOk()->assertJsonPath('mensagem', 'Sessão encerrada com sucesso.');
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
