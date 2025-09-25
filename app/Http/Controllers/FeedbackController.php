<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('usuario.contato');
    }

    public function store(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $dados = $request->validate([
            'nome' => 'required|string|min:3|max:120',
            'email' => 'required|string|lowercase|email:rfc|max:255',
            'assunto' => 'required|string|min:5|max:150',
            'mensagem' => 'required|string|min:10|max:2000',
        ]);

        RateLimiter::hit($this->throttleKey($request), 180);

        Feedback::create([
            'nome' => $this->sanitizeInput($dados['nome']),
            'email' => strtolower($dados['email']),
            'assunto' => $this->sanitizeInput($dados['assunto']),
            'mensagem' => $this->sanitizeInput($dados['mensagem'], false),
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 255),
        ]);

        return redirect()->route('feedback.create')->with('status', 'Mensagem enviada com sucesso! Obrigado pelo contato.');
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 3)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => 'Você realizou muitas tentativas em pouco tempo. Tente novamente em '.$seconds.' segundos.',
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return 'feedback|'.Str::lower($request->ip());
    }

    protected function sanitizeInput(string $texto, bool $compactar = true): string
    {
        $semScripts = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', ' ', $texto);
        $limpo = strip_tags($semScripts);

        return $compactar ? Str::squish($limpo) : trim(preg_replace('/\s+/u', ' ', $limpo));
    }
}
