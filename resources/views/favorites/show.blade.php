<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $list->nameL }} | YAAM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background: #090b14;
            color: #f8f9fa;
        }

        .movie-card {
            background: #12172a;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 1rem;
            overflow: hidden;
            height: 100%;
        }

        .movie-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            background: #1f243a;
        }

        .movie-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .text-soft {
            color: #a7acc0;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="container py-4 py-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">{{ $list->nameL }}</h1>
                <p class="mb-0 text-soft">{{ $items->count() }} película(s) guardada(s) en esta lista.</p>
            </div>
            <a href="{{ route('favorites.index') }}" class="btn btn-outline-light">Volver a listas</a>
        </div>

        @if ($items->isEmpty())
            <div class="alert alert-secondary" role="alert">
                Esta lista está vacía. Agrega películas desde el detalle de una película.
            </div>
        @else
            <div class="row g-3 g-md-4">
                @foreach ($items as $item)
                    @php
                        $movie = $item->movie;
                        $posterUrl =
                            $movie && $movie->posterPath
                                ? 'https://image.tmdb.org/t/p/w500' . $movie->posterPath
                                : 'https://via.placeholder.com/500x750?text=Sin+poster';
                    @endphp

                    @if ($movie)
                        <div class="col-6 col-md-4 col-lg-3">
                            <article class="movie-card">
                                <a class="movie-link"
                                    href="{{ route('movies.detail', ['id' => $movie->external_id]) }}">
                                    <img src="{{ $posterUrl }}" alt="{{ $movie->title }}">
                                    <div class="p-3">
                                        <h2 class="h6 mb-1">{{ $movie->title }}</h2>
                                        <p class="small text-soft mb-0">Guardada:
                                            {{ $item->created_at?->format('d/m/Y H:i') }}</p>
                                    </div>
                                </a>

                                <div class="px-3 pb-3">
                                    <form method="POST"
                                        action="{{ route('favorites.items.destroy', ['list' => $list->id, 'item' => $item->id]) }}"
                                        onsubmit="return confirm('¿Quitar esta película de la lista?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">Quitar de
                                            lista</button>
                                    </form>
                                </div>
                            </article>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
