<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consulta de Usuarios</title>
</head>

<body>

    @extends('layouts.app')

    @section('content')
        <h1>Usuarios Registrados</h1>

        <div class="d-flex justify-content-end mb-2">

            <a href="{{ route('usuarios.create') }}" class="btn btn-success mb-3">
                Nuevo Usuario
            </a>



        </div>

        <table class="table table-striped table-hover">
            <thread>
                <tr>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th>EMAIL</th>
                    <th>PASSOWRD</th>
                </tr>
            </thread>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <!-- Nombre de la BD -->
                        <td> {{ $usuario->id }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->passowrd }}</td>
                        <td>
                            <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger" onclick="return confirm('¿Desea eliminar el libro?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endsection
</body>

</html>
