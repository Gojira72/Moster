<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginModel;

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
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $usuario = LoginModel::where('emailUsuario', $request->email)->first();

        if ($usuario && \Hash::check($request->password, $usuario->senhaUsuario)) {
            Auth::login($usuario);
            $request->session()->regenerate();
            return redirect()->route('teste'); // redireciona para página restrita
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas. Verifique seu e-mail e senha.'
        ])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.index');
    }
}
