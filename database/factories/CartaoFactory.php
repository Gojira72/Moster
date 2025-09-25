<?php

namespace Database\Factories;

use App\Models\Cartao;
use App\Models\LoginModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cartao>
 */
class CartaoFactory extends Factory
{
    protected $model = Cartao::class;

    public function definition(): array
    {
        $limite = $this->faker->numberBetween(4000, 12000);
        $ultimosDigitos = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        return [
            'usuario_id' => LoginModel::factory(),
            'apelido' => 'Cartão Nubank',
            'bandeira' => $this->faker->randomElement(['Mastercard', 'Visa']),
            'ultimos_digitos' => $ultimosDigitos,
            'limite_total' => $limite,
            'limite_disponivel' => $limite,
            'status' => 'ativo',
            'vencimento_fatura' => now()->addDays(15)->toDateString(),
        ];
    }
}
