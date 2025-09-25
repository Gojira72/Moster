<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\LoginModel;
use Illuminate\Support\Facades\Auth;
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

        $resposta->assertRedirect(route('teste'));
        $resposta->assertSessionHas('success', 'Cadastro realizado com sucesso!');

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

    public function test_login_redireciona_para_area_restrita_quando_nao_ha_destino_definido(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1')->create([
            'emailUsuario' => 'teste@gmail.com',
        ]);

        $resposta = $this->post(route('login.store'), [
            'email' => 'teste@gmail.com',
            'password' => 'SenhaForte1',
        ]);

        $resposta->assertRedirect(route('teste'));
        $this->assertAuthenticatedAs($usuario);
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

    public function test_login_sem_manter_sessao_permite_visualizar_pagina_restrita(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1')->create([
            'emailUsuario' => 'teste@gmail.com',
        ]);

        $respostaLogin = $this->post(route('login.store'), [
            'email' => 'teste@gmail.com',
            'password' => 'SenhaForte1',
        ]);

        $respostaLogin->assertRedirect(route('teste'));
        $this->assertAuthenticatedAs($usuario);

        $respostaRestrita = $this->get(route('teste'));
        $respostaRestrita->assertOk();
        $respostaRestrita->assertSeeText('Login realizado com sucesso!');
    }

    public function test_usuario_consegue_deslogar_e_perde_acesso_a_pagina_restrita(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1')->create([
            'emailUsuario' => 'teste@gmail.com',
        ]);

        $this->post(route('login.store'), [
            'email' => 'teste@gmail.com',
            'password' => 'SenhaForte1',
        ]);

        $this->assertAuthenticatedAs($usuario);

        $respostaLogout = $this->post(route('logout'));

        $respostaLogout->assertRedirect(route('login'));
        $this->assertGuest();

        $respostaRestrita = $this->get(route('teste'));
        $respostaRestrita->assertRedirect(route('login'));
    }

    public function test_logout_limpa_cookie_de_lembrar_usuario(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1')->create([
            'emailUsuario' => 'teste@gmail.com',
        ]);

        $respostaLogin = $this->post(route('login.store'), [
            'email' => 'teste@gmail.com',
            'password' => 'SenhaForte1',
            'remember' => '1',
        ]);

        $respostaLogin->assertRedirect(route('teste'));
        $this->assertAuthenticatedAs($usuario);

        $recallerName = Auth::guard()->getRecallerName();
        $recallerCookie = $respostaLogin->getCookie($recallerName, false);

        $this->assertNotNull($recallerCookie, 'O cookie de "lembrar" deveria estar presente após o login.');

        $respostaLogout = $this
            ->withUnencryptedCookie($recallerCookie->getName(), $recallerCookie->getValue())
            ->post(route('logout'));

        $respostaLogout->assertRedirect(route('login'));
        $respostaLogout->assertCookieExpired($recallerName);
        $this->assertGuest();
    }

    public function test_pagina_teste_exibe_dados_do_usuario_logado(): void
    {
        $usuario = LoginModel::factory()->create([
            'nomeUsuario' => 'Maria Teste',
            'emailUsuario' => 'maria@example.com',
        ]);

        $this->actingAs($usuario);

        $resposta = $this->get(route('teste'));

        $resposta->assertOk();
        $resposta->assertSeeText('Login realizado com sucesso!');
        $resposta->assertSeeText('Você está autenticado no sistema.');
        $resposta->assertSeeText('Maria Teste');
        $resposta->assertSeeText('maria@example.com');
        $resposta->assertSeeText((string) $usuario->id);
        $resposta->assertSeeText('Registrado em');
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
