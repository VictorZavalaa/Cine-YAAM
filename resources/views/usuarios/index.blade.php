@extends('layouts.app')

@section('content')

    @include('partials.alerts')

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
                    <th>TELÉFONO</th>
                    <th>ADMIN</th>
                    <th>ACCIONES</th>
                </tr>
            </thread>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <!-- Nombre de la BD -->
                        <td> {{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->phone }}</td>
                        <td>
                            @if($usuario->is_admin)
                                <span class="badge bg-primary">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
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
