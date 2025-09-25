<?php

namespace Database\Factories;

use App\Enums\TipoTransacao;
use App\Models\Conta;
use App\Models\Transacao;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transacao>
 */
class TransacaoFactory extends Factory
{
    protected $model = Transacao::class;

    public function definition(): array
    {
        $tipo = $this->faker->randomElement([
            TipoTransacao::Entrada,
            TipoTransacao::Saida,
            TipoTransacao::Transferencia,
        ]);

        $valor = $this->faker->randomFloat(2, 10, 800);

        return [
            'conta_id' => Conta::factory(),
            'tipo' => $tipo,
            'categoria' => $this->faker->randomElement(['compras', 'transferencia', 'pagamento', 'juros']),
            'valor' => $valor,
            'descricao' => $this->faker->sentence(3),
            'contraparte' => $this->faker->company(),
            'ocorreu_em' => now()->subDays($this->faker->numberBetween(0, 30)),
        ];
    }
}
