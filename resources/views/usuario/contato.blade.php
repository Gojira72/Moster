<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Ghoul High</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('{{ asset('img/fundo.png') }}') no-repeat center center/cover;
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #1c002e;
        }

        main {
            padding-top: 140px;
            padding-bottom: 60px;
        }

        .card-contato {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            border: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #582574, #7e34a5);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #43195a, #682c87);
        }
    </style>
</head>
<body>
    @include('partials.header')

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card card-contato p-4">
                    <h1 class="h3 mb-4 text-center">Fale conosco</h1>
                    <form action="{{ route('feedback.store') }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', optional(auth()->user())->nomeUsuario ?? '') }}" required minlength="3" autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', optional(auth()->user())->emailUsuario ?? optional(auth()->user())->email ?? '') }}" required autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label for="assunto" class="form-label">Assunto</label>
                            <input type="text" class="form-control" id="assunto" name="assunto" value="{{ old('assunto') }}" required minlength="5" maxlength="150">
                        </div>
                        <div class="mb-4">
                            <label for="mensagem" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required minlength="10" maxlength="2000" placeholder="Conte-nos como podemos ajudar">{{ old('mensagem') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar feedback</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
