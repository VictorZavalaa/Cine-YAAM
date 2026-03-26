<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrar pelicula</title>
</head>

<body>
    @extends('layouts.app')

    @section('content')
        <h1>Registro de peliculas</h1>

        <form action="{{ route('movies.store') }}" method="POST">

            <!-- Proteccion de laravel para usar un formulario. OBLIGATORIO-->
            @csrf

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"> <i class="fa-brands fa-sourcetree"></i></span>
                <input type="text" name="sourceM" placeholder="SourceM" class="form-control">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"> <i class="fa-solid fa-id-card"></i></span>
                <input type="text" name="external_id" placeholder="External ID" class="form-control">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"> <i class="fa-solid fa-film"></i></span>
                <input type="text" name="title" placeholder="Title" class="form-control">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"> <i class="fa-solid fa-file-lines"></i></span>
                <textarea name="overview" placeholder="Overview" class="form-control"></textarea>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"> <i class="fa-solid fa-calendar-days"></i></span>
                <input type="datetime-local" name="releaseDate" placeholder="Release Date" class="form-control">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"> <i class="fa-solid fa-image"></i></span>
                <input type="text" name="posterPath" placeholder="Poster Path" class="form-control">
            </div>





            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"> </i> Guardar </button>

        </form>

        <div class="d-flex justify-content-end">
            <a href="{{ route('movies.index') }}" class="btn btn-danger">
                Volver
            </a>

        </div>
    @endsection

</body>

</html>
