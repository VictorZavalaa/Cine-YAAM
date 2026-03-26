@extends('layouts.app')

@section('content')
        <h1>Registro de usuarios</h1>

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            
            <input type="text" name="name" placeholder="Nombre" class="form-control mb-2" required>
            <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
            <input type="text" name="phone" placeholder="Teléfono" class="form-control mb-2" required>
            <input type="password" name="password" placeholder="Contraseña" class="form-control mb-2" required>
            
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="is_admin">
                <label class="form-check-label" for="is_admin">Es administrador</label>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
        </form>
    @endsection
