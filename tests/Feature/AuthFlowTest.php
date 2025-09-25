<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\LoginModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_pode_registrar_com_senha_forte(): void
    {
        $resposta = $this->post(route('register.store'), [
            'name' => 'Usuário Teste',
            'email' => 'teste@gmail.com',
            'password' => 'SenhaForte1',
            'password_confirmation' => 'SenhaForte1',
        ]);

        $resposta->assertRedirect(route('welcome'));

        $usuario = LoginModel::where('emailUsuario', 'teste@gmail.com')->first();
        $this->assertNotNull($usuario);
        $this->assertTrue(Hash::check('SenhaForte1', $usuario->senhaUsuario));
    }

    public function test_login_bloqueia_apos_tentativas_excedidas(): void
    {
        LoginModel::factory()->withPassword('SenhaForte1')->create([
            'emailUsuario' => 'teste@gmail.com',
        ]);

        $throttleKey = strtolower('teste@gmail.com').'|127.0.0.1';
        RateLimiter::clear($throttleKey);

        for ($i = 0; $i < 5; $i++) {
            $resposta = $this->from(route('login'))
                ->post(route('login.store'), [
                    'email' => 'teste@gmail.com',
                    'password' => 'SenhaErrada1',
                ]);

            $resposta->assertRedirect(route('login'));
            $resposta->assertSessionHasErrors('email');
        }

        $resposta = $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => 'teste@gmail.com',
                'password' => 'SenhaErrada1',
            ]);

        $resposta->assertRedirect(route('login'));
        $resposta->assertSessionHasErrors('email');
        $mensagemErro = session('errors')->first('email');
        $this->assertTrue(
            str_contains($mensagemErro, 'segund') || str_contains($mensagemErro, 'second'),
            'A mensagem de bloqueio deve informar o tempo restante.'
        );
        $this->assertGuest();
    }

    public function test_login_redireciona_para_pagina_original(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1')->create([
            'emailUsuario' => 'teste@gmail.com',
        ]);

        $resposta = $this->withSession(['url.intended' => route('teste')])
            ->post(route('login.store'), [
                'email' => 'teste@gmail.com',
                'password' => 'SenhaForte1',
            ]);

        $resposta->assertRedirect(route('teste'));
        $this->assertAuthenticatedAs($usuario);
    }

    public function test_feedback_e_sanitizado_e_armazenado(): void
    {
        $resposta = $this->post(route('feedback.store'), [
            'nome' => ' Visitante <strong>Curioso</strong> ',
            'email' => 'visitante@gmail.com',
            'assunto' => ' Ajuda <script>alert(1)</script> urgente ',
            'mensagem' => "<script>alert('xss')</script> Preciso de suporte!   ",
        ]);

        $resposta->assertRedirect(route('feedback.create'));
        $resposta->assertSessionHas('status');

        $feedback = Feedback::first();
        $this->assertNotNull($feedback);
        $this->assertSame('visitante@gmail.com', $feedback->email);
        $this->assertStringNotContainsString('<', $feedback->assunto);
        $this->assertStringNotContainsString('<', $feedback->mensagem);
        $this->assertSame('Ajuda urgente', $feedback->assunto);
    }
}
