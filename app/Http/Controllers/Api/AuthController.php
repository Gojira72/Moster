<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UsuarioResource;
use App\Models\LoginModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $dados = $request->validated();
        $emailNormalizado = strtolower($dados['email']);
        $chaveThrottle = Str::lower($emailNormalizado).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($chaveThrottle, 5)) {
            $segundos = RateLimiter::availableIn($chaveThrottle);

            throw ValidationException::withMessages([
                'email' => "Muitas tentativas. Tente novamente em {$segundos} segundos.",
            ])->status(429);
        }

        $usuario = LoginModel::where('emailUsuario', $emailNormalizado)->first();

        if (! $usuario || ! Hash::check($dados['password'], $usuario->senhaUsuario)) {
            RateLimiter::hit($chaveThrottle);

            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        RateLimiter::clear($chaveThrottle);

        $usuario->tokens()->delete();

        $expiraEm = now()->addMonths(6);
        $token = $usuario->createToken('mobile', abilities: ['*'], expiresAt: $expiraEm);

        return response()->json([
            'mensagem' => 'Autenticação realizada com sucesso.',
            'dados' => [
                'token' => $token->plainTextToken,
                'tipoToken' => 'Bearer',
                'expiraEm' => $expiraEm->toIso8601String(),
                'usuario' => new UsuarioResource($usuario),
            ],
        ]);
    }

    public function logout(): JsonResponse
    {
        $token = request()->user()?->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'mensagem' => 'Sessão encerrada com sucesso.',
        ]);
    }
}
