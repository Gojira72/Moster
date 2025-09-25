<?php

namespace Database\Factories;

use App\Models\LoginModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'nomeUsuario' => Str::squish($this->faker->name()),
            'emailUsuario' => strtolower($this->faker->unique()->safeEmail()),
            'senhaUsuario' => Hash::make($senha),
            'telefone' => $this->faker->numerify('119########'),
            'avatar_url' => null,
            'documento' => Str::upper($this->faker->bothify('###########')),
        ];
    }

    public function withPassword(string $password): self
    {
        return $this->state(fn () => [
            'senhaUsuario' => Hash::make($password),
        ]);
    }
}
