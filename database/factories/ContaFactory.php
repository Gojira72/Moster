<?php

namespace Database\Factories;

use App\Models\Conta;
use App\Models\LoginModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conta>
 */
class ContaFactory extends Factory
{
    protected $model = Conta::class;

    public function definition(): array
    {
        $limite = $this->faker->numberBetween(2000, 8000);
        $saldo = $this->faker->numberBetween(500, 3000);

        return [
            'usuario_id' => LoginModel::factory(),
            'saldo_atual' => $saldo,
            'limite_credito' => $limite,
            'limite_disponivel' => $limite,
        ];
    }
}
