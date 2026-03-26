<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>

<body>
    @extends('layouts.app')
    @section('content')
        <h1>Registro de usuarios</h1>

        <form action="{{ route('registro.store') }}" method="POST">

            @csrf

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic addon1"> <i class="fa-solid fa-user"></i></span>
                <input type="text" name="name" placeholder="Name" class="form-control">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic addon1"> <i class="fa-solid fa-envelope"></i></span>
                <input type="email" name="email" placeholder="Email" class="form-control">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic addon1"> <i class="fa-solid fa-phone"></i></span>
                <input type="text" name="phone" placeholder="Phone" class="form-control">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic addon1"> <i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" placeholder="Password" class="form-control">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic addon1"> <i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control">
            </div>


            @if (auth()->check() && auth()->user()->is_admin)
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="fa-solid fa-user-shield"></i>
                    </span>
                    <div class="form-control">
                        <input class="form-check-input me-2" type="checkbox" name="is_admin" value="1" id="is_admin">
                        <label class="form-check-label" for="is_admin">
                            Es administrador
                        </label>
                    </div>
                </div>
            @endif

            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"> </i> Guardar </button>
        </form>
    @endsection
</body>

</html>
