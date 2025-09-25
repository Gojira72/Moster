<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginModel; // Modelo que interage com a tabela tb_usuarios
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // Exibe a view de cadastro
    public function index()
    {
        return view('auth.register');
    }

    // Processa o cadastro do usuário
    public function store(Request $request)
    {
        // Validação dos dados enviados
        $validatedData = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|lowercase|email:rfc|max:255|unique:tb_usuarios,emailUsuario',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ], [
            'password.regex' => 'A senha deve conter ao menos uma letra maiúscula, uma minúscula e um número.',
        ]);

        // Criação do usuário com senha criptografada
        $usuarioo = LoginModel::create([
            'nomeUsuario' => Str::squish($validatedData['name']),
            'emailUsuario' => strtolower($validatedData['email']),
            'senhaUsuario' => Hash::make($validatedData['password']),
        ]);

        // Faz o login do usuário recém-cadastrado
        Auth::login($usuarioo);

        // Redireciona para a área autenticada
        return redirect()->route('teste')->with('success', 'Cadastro realizado com sucesso!');
    }
}