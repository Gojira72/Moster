<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginModel;



class LoginController extends Controller
{
    // Exibe a view de login
    public function index()
    {
        return view('auth.login');
    }

    // Processa o login
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6'
        ]);

        $usuario = LoginModel::where('emailUsuario', $request->email)->first();

        if ($usuario && \Hash::check($request->password, $usuario->senhaUsuario)) {
            Auth::login($usuario);
            $request->session()->regenerate();

            return redirect()->route('teste'); // Página restrita
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas. Verifique seu e-mail e senha.'
        ])->withInput();
    }

    // Logout
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
