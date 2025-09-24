<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUTUTU</title>
    <link rel="website icon" type="image/png" href="images/logoEtecRoxo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #6700a3ff;
            color: white;
            text-align: center;
            padding-top: 50px;
        }
    </style>
</head>
<body>

<h1>Hello World</h1>

@if(Auth::check())
    <p>Você está logado como: {{ Auth::user()->nomeUsuario ?? Auth::user()->name ?? Auth::user()->email }}</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-light">Sair</button>
    </form>
@else
    <p>Nenhum usuário logado</p>
@endif

</body>
</html>
