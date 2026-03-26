@extends('layouts.app')

@section('content')

    @include('partials.alerts')

    <h1>Movies Registrados</h1>
        <div class="d-flex justify-content-end mb-2">
            <a href="{{ route('movies.create') }}" class="btn btn-success mb-3 me-3">
                <i class="fa-regular fa-square-plus"></i> Nuevo Movie
            </a>
            <form action="{{ route('cerrar') }}" method="POST">
                @csrf
                <button class="btn btn-danger"><i class="fa-solid fa-right-from-bracket me-3"></i>Cerrar sesion</button>
            </form>

            @if (auth()->check() && auth()->user()->is_admin)
                <a href="{{ route('admin-dashboard') }}" class="btn btn-secondary ms-3 mb-3">
                    <i class="fa-solid fa-user-tie"></i> Panel admin
                </a>
            @endif


        </div>
        <table class="table table-striped table-hover">
            <thread>
                <tr>
                    <th>ID</th>
                    <th>SOURCE M</th>
                    <th>EXTERNAL ID</th>
                    <th>TITLE</th>
                    <th>OVERVIEW</th>
                    <th>RELEASE DATE</th>
                    <th>POSTER PATH</th>
                </tr>
            </thread>
            <tbody>
                @foreach ($movies as $movie)
                    <tr>
                        <!-- Nombre de la BD -->
                        <td> {{ $movie->id }}</td>
                        <td>{{ $movie->sourceM }}</td>
                        <td>{{ $movie->external_id }}</td>
                        <td>{{ $movie->title }}</td>
                        <td>{{ $movie->overview }}</td>
                        <td>{{ $movie->releaseDate }}</td>
                        <td>{{ $movie->posterPath }}</td>
                        <td>
                            <a href="{{ route('movies.edit', $movie) }}" class="btn btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('movies.destroy', $movie) }}" method="POST" class="d-inline">
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
