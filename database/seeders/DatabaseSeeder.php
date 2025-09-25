<?php

namespace Database\Seeders;

use App\Enums\TipoTransacao;
use App\Models\Cartao;
use App\Models\Conta;
use App\Models\LoginModel;
use App\Models\Transacao;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $usuario = LoginModel::factory()
            ->withPassword('SenhaForte1!')
            ->create([
                'nomeUsuario' => 'Cliente Demo',
                'emailUsuario' => 'cliente@nubank.com',
                'telefone' => '11999998888',
                'avatar_url' => null,
                'documento' => '12345678900',
            ]);

        $conta = Conta::factory()->for($usuario, 'usuario')->create([
            'saldo_atual' => 5234.87,
            'limite_credito' => 6000.00,
            'limite_disponivel' => 4800.00,
        ]);

        Cartao::factory()->for($usuario, 'usuario')->create([
            'apelido' => 'Cartão Roxo',
            'bandeira' => 'Mastercard',
            'ultimos_digitos' => '1234',
            'limite_total' => 6000.00,
            'limite_disponivel' => 4200.00,
            'status' => 'ativo',
            'vencimento_fatura' => now()->addDays(15)->toDateString(),
        ]);

        $transacoesFixas = [
            [
                'tipo' => TipoTransacao::Entrada,
                'categoria' => 'salario',
                'valor' => 7500,
                'descricao' => 'Pagamento mensal',
                'contraparte' => 'Empresa Exemplo',
                'ocorreu_em' => now()->subDays(3),
            ],
            [
                'tipo' => TipoTransacao::Saida,
                'categoria' => 'compras',
                'valor' => 280.45,
                'descricao' => 'Supermercado',
                'contraparte' => 'Supermercado Central',
                'ocorreu_em' => now()->subDays(2),
            ],
            [
                'tipo' => TipoTransacao::Transferencia,
                'categoria' => 'transferencia',
                'valor' => 150.00,
                'descricao' => 'Transferência para João',
                'contraparte' => 'João da Silva',
                'ocorreu_em' => now()->subDay(),
            ],
        ];

        foreach ($transacoesFixas as $transacao) {
            Transacao::create(array_merge($transacao, ['conta_id' => $conta->id]));
        }

        Transacao::factory(7)->for($conta, 'conta')->create();
    }
}
