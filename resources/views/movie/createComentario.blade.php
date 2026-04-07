<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crear comentario | YAAM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background: #090b14;
            color: #f8f9fa;
        }

        .data-card {
            background: #11172b;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 1rem;
        }

        .poster {
            width: 100%;
            max-width: 220px;
            border-radius: .8rem;
            border: 1px solid rgba(255, 255, 255, .15);
        }

        .text-soft {
            color: #a7acc0;
        }

        .meta-pill {
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(255, 255, 255, .05);
            border-radius: 999px;
            padding: .35rem .7rem;
            font-size: .82rem;
            display: inline-block;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="container py-4 py-md-5">
        @if ($error || !$movie)
            <div class="alert alert-warning" role="alert">
                {{ $error ?? 'No se pudo cargar la película para comentar.' }}
            </div>
            <a href="{{ route('search.index') }}" class="btn btn-outline-light">Volver al buscador</a>
        @else
            @php
                $title = $movie['title'] ?? 'Sin título';
                $originalTitle = $movie['original_title'] ?? null;
                $overview = $movie['overview'] ?? 'Sin descripción disponible.';
                $releaseDate = $movie['release_date'] ?? null;
                $posterPath = $movie['poster_path'] ?? null;
                $posterUrl = $posterPath
                    ? 'https://image.tmdb.org/t/p/w500' . $posterPath
                    : 'https://via.placeholder.com/500x750?text=Sin+poster';
                $voteAverage = isset($movie['vote_average']) ? number_format((float) $movie['vote_average'], 1) : 'N/D';
                $voteCount = $movie['vote_count'] ?? 0;
                $runtime = $movie['runtime'] ?? null;
                $originalLanguage = $movie['original_language'] ?? 'N/D';
                $popularity = isset($movie['popularity']) ? number_format((float) $movie['popularity'], 1) : 'N/D';
                $genres = collect($movie['genres'] ?? [])
                    ->pluck('name')
                    ->filter()
                    ->implode(', ');
                $countries = collect($movie['production_countries'] ?? [])
                    ->pluck('name')
                    ->filter()
                    ->implode(', ');
            @endphp

            <section class="data-card p-4 mb-4">
                <div class="row g-4 align-items-start">
                    <div class="col-md-3 text-center text-md-start">
                        <img class="poster" src="{{ $posterUrl }}" alt="{{ $title }}">
                    </div>
                    <div class="col-md-9">
                        <h1 class="h3 mb-2">{{ $title }}</h1>

                        @if ($originalTitle && $originalTitle !== $title)
                            <p class="text-soft mb-2">Título original: {{ $originalTitle }}</p>
                        @endif

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="meta-pill">⭐ {{ $voteAverage }} ({{ $voteCount }} votos)</span>
                            <span class="meta-pill">Estreno: {{ $releaseDate ?: 'N/D' }}</span>
                            <span class="meta-pill">Duración: {{ $runtime ? $runtime . ' min' : 'N/D' }}</span>
                            <span class="meta-pill">Idioma: {{ strtoupper($originalLanguage) }}</span>
                            <span class="meta-pill">Popularidad: {{ $popularity }}</span>
                        </div>

                        <p class="mb-2 text-soft"><strong>Géneros:</strong> {{ $genres ?: 'N/D' }}</p>
                        <p class="mb-3 text-soft"><strong>Países:</strong> {{ $countries ?: 'N/D' }}</p>

                        <p class="mb-0 text-soft">{{ $overview }}</p>
                    </div>
                </div>
            </section>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $errorItem)
                            <li>{{ $errorItem }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="data-card p-4">
                <h2 class="h5 mb-3">Deja tu comentario</h2>
                <form method="POST" action="{{ route('comments.storeComentario') }}">
                    @csrf

                    <input type="hidden" name="tmdb_id" value="{{ $movie['id'] ?? '' }}">
                    <input type="hidden" name="title" value="{{ $title }}">
                    <input type="hidden" name="overview" value="{{ $overview }}">
                    <input type="hidden" name="release_date" value="{{ $releaseDate }}">
                    <input type="hidden" name="poster_path" value="{{ $posterPath }}">

                    <div class="mb-3">
                        <label for="bodyComment" class="form-label">Comentario</label>
                        <textarea id="bodyComment" name="bodyComment" class="form-control" rows="8" minlength="3" maxlength="1500"
                            placeholder="Escribe aquí tu opinión de la película..." required>{{ old('bodyComment') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Publicar comentario</button>
                        <a href="{{ route('movies.detail', ['id' => $movie['id']]) }}"
                            class="btn btn-outline-light">Cancelar</a>
                    </div>
                </form>
            </section>
        @endif
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
