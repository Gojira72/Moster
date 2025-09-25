<?php

namespace Tests\Feature\Api;

use App\Enums\TipoTransacao;
use App\Models\Conta;
use App\Models\LoginModel;
use App\Models\Transacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceiroApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_recebe_dados_da_conta(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1!')->create([
            'emailUsuario' => 'cliente@exemplo.com',
        ]);
        $conta = Conta::factory()->for($usuario, 'usuario')->create([
            'saldo_atual' => 1500.75,
            'limite_credito' => 4000,
            'limite_disponivel' => 3200,
        ]);

        $token = $usuario->createToken('mobile');

        $resposta = $this->withToken($token->plainTextToken)->getJson('/api/accounts/me');

        $resposta->assertOk()
            ->assertJsonPath('dados.id', $conta->id)
            ->assertJsonPath('dados.saldoAtual', 1500.75);

        $dados = $resposta->json('dados');
        $this->assertEqualsWithDelta(4000.0, $dados['limiteCredito'], 0.001);
        $this->assertEqualsWithDelta(3200.0, $dados['limiteDisponivel'], 0.001);
    }

    public function test_listagem_de_transacoes_retorna_paginada(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1!')->create([
            'emailUsuario' => 'cliente@exemplo.com',
        ]);
        $conta = Conta::factory()->for($usuario, 'usuario')->create();
        Transacao::factory(25)->for($conta, 'conta')->create();

        $token = $usuario->createToken('mobile');

        $resposta = $this->withToken($token->plainTextToken)->getJson('/api/transactions?por_pagina=10');

        $resposta->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.paginaAtual', 1)
            ->assertJsonPath('meta.totalRegistros', 25);
    }

    public function test_transferencia_debita_saldo_e_cria_transacao(): void
    {
        $usuario = LoginModel::factory()->withPassword('SenhaForte1!')->create([
            'emailUsuario' => 'cliente@exemplo.com',
        ]);
        $conta = Conta::factory()->for($usuario, 'usuario')->create([
            'saldo_atual' => 500,
        ]);

        $token = $usuario->createToken('mobile');

        $resposta = $this->withToken($token->plainTextToken)->postJson('/api/transfers', [
            'valor' => 100,
            'descricao' => 'Transferência teste',
            'destinatario' => 'Fulano de Tal',
        ]);

        $resposta->assertCreated()
            ->assertJsonPath('mensagem', 'Transferência registrada com sucesso.');

        $dados = $resposta->json('dados');
        $this->assertEqualsWithDelta(400.0, $dados['conta']['saldoAtual'], 0.001);
        $this->assertSame(TipoTransacao::Transferencia->value, $dados['transacao']['tipo']);

        $this->assertDatabaseHas('transacoes', [
            'conta_id' => $conta->id,
            'descricao' => 'Transferência teste',
            'tipo' => TipoTransacao::Transferencia->value,
        ]);
    }
}
