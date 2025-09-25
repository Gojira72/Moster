<?php

namespace Database\Factories;

use App\Models\LoginModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<LoginModel>
 */
class LoginModelFactory extends Factory
{
    protected $model = LoginModel::class;

    public function definition(): array
    {
        $senha = 'Senha123';

        return [
            'nomeUsuario' => $this->faker->name(),
            'emailUsuario' => $this->faker->unique()->safeEmail(),
            'senhaUsuario' => Hash::make($senha),
        ];
    }

    public function withPassword(string $password): self
    {
        return $this->state(fn () => [
            'senhaUsuario' => Hash::make($password),
        ]);
    }
}
