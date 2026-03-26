<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar Usuario</title>
</head>

<body>
    @extends('layouts.app')
    @section('content')
        <h1>Editar usuario: {{ $usuario->nombre }}</h1>

        <form action="{{ route('usuarios.update', $usuario) }}" method="POST">

            <!--Uso obligatorio para usar un formulario en laravel-->
            @csrf
            @method('PUT')

            <input type="text" name="nombre" value="{{ $usuario->nombre }}" placeholder="Nombre" class="form=control">
            <br>
            <input type="text" name="email" value="{{ $usuario->email }}" placeholder="Email" class="form=control">
            <br>
            <input type="text" name="password" value="{{ $usuario->password }}" placeholder="Password"
                class="form=control">
            <br>

            <button type="Submit" class="btn btn-success">Guardar</button>
        </form>

        <div class="d-flex justify-content-end">
            <a href="{{ route('usuarios.index') }}" class="btn btn-danger">
                Volver
            </a>

        </div>
    @endsection

</body>

</html>
