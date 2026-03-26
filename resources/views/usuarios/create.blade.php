<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrar usuario</title>
</head>

<body>
    @extends('layouts.app')

    @section('content')
        <h1>Registro de usuarios</h1>

        <form action="{{ route('usuarios.store') }}" method="POST">

            <!-- Proteccion de laravel para usar un formulario. OBLIGATORIO-->
            @csrf
            <input type="text" name="nombre" placeholder="Nombre">
            <br><br>
            <input type="text" name="email" placeholder="Email">
            <br><br>
            <input type="text" name="password" placeholder="Password">
            <br><br>

            <button type="submit">Guardar</button>

        </form>
    @endsection

</body>

</html>
