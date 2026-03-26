@extends('layouts.app')

@section('content')

    @include('partials.alerts')

    <h1>Editar usuario: {{ $usuario->name }}</h1>

    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="text" name="name" value="{{ $usuario->name }}" placeholder="Nombre" class="form-control mb-2" required>
        <input type="email" name="email" value="{{ $usuario->email }}" placeholder="Email" class="form-control mb-2" required>
        <input type="text" name="phone" value="{{ $usuario->phone }}" placeholder="Teléfono" class="form-control mb-2" required>
        <input type="password" name="password" placeholder="Nueva Contraseña (dejar en blanco para no cambiar)" class="form-control mb-2">
        
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="is_admin" {{ $usuario->is_admin ? 'checked' : '' }}>
            <label class="form-check-label" for="is_admin">Es administrador</label>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
    </form>

    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route('usuarios.index') }}" class="btn btn-danger">Volver</a>
    </div>
@endsection
