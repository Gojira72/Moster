<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área logada</title>
    <link rel="website icon" type="image/png" href="images/logoEtecRoxo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #6700a3ff 0%, #44006b 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 1.5rem 3rem rgba(0, 0, 0, 0.25);
        }

        .card-header {
            border-radius: 20px 20px 0 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card text-dark">
                <div class="card-header bg-white text-center py-4">
                    <h1 class="h3 mb-0">Login realizado com sucesso!</h1>
                </div>
                <div class="card-body bg-light">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @auth
                        @php($usuario = auth()->user())
                        <p class="text-center text-muted mb-4">Você está autenticado no sistema. Confira abaixo seus dados cadastrados:</p>

                        <dl class="row g-3">
                            <dt class="col-sm-4 text-uppercase text-muted">ID</dt>
                            <dd class="col-sm-8">{{ $usuario->id }}</dd>

                            <dt class="col-sm-4 text-uppercase text-muted">Nome</dt>
                            <dd class="col-sm-8">{{ $usuario->nomeUsuario ?? '—' }}</dd>

                            <dt class="col-sm-4 text-uppercase text-muted">E-mail</dt>
                            <dd class="col-sm-8">{{ $usuario->emailUsuario ?? '—' }}</dd>

                            <dt class="col-sm-4 text-uppercase text-muted">Registrado em</dt>
                            <dd class="col-sm-8">
                                {{ optional($usuario->created_at)->format('d/m/Y \à\s H:i') ?? '—' }}
                            </dd>
                        </dl>

                        <div class="d-grid gap-2 mt-4">
                            <a href="{{ route('welcome') }}" class="btn btn-primary">Ir para a página inicial</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">Sair da conta</button>
                            </form>
                        </div>
                    @else
                        <p class="text-center text-muted">Nenhum usuário logado no momento.</p>
                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="btn btn-primary">Fazer login</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
