<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Exibe o formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Processa o login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email:rfc',
            'password' => 'required|string|min:8',
            'remember' => 'sometimes|boolean',
        ]);

        $this->ensureIsNotRateLimited($request);

        $autenticado = Auth::attempt([
            'emailUsuario' => strtolower($credentials['email']),
            'password' => $credentials['password'],
        ], $request->boolean('remember'));

        if (! $autenticado) {
            RateLimiter::hit($this->throttleKey($request));

            throw ValidationException::withMessages([
                'email' => 'Não foi possível autenticar com as credenciais fornecidas.',
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));

        $request->session()->regenerate();

        return redirect()->intended(route('teste'));
    }

    // Logout
    public function logout(Request $request)
    {
        $guard = Auth::guard();

        if ($guard->check()) {
            $guard->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if (method_exists($guard, 'getRecallerName')) {
                Cookie::queue(Cookie::forget($guard->getRecallerName()));
            }
        }

        return redirect()->route('login');
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return mb_strtolower((string) $request->input('email')).'|'.$request->ip();
    }
}
