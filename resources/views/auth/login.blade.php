@extends('layouts.app')

@section('content')

    @include('partials.alerts')

    <h1>Iniciar sesion</h1>

        <form action="{{ route('acceso.store') }}" method="POST">

            @csrf


            <input type="email" name="email" placeholder="Correo" class="form-control" required>
            <br>
            <input type="password" name="password" placeholder="Contraseña" class="form-control" required>
            <br>

            <button type="submit" class="btn btn-success">Iniciar sesion</button>
        </form>
    @endsection
