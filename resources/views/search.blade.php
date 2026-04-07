<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resultados de búsqueda | YAAM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        :root {
            --bg-main: #090b14;
            --bg-surface: #12172a;
            --text-soft: #a7acc0;
        }

        body {
            background: radial-gradient(circle at top, #121938 0%, var(--bg-main) 42%);
            color: #f8f9fa;
        }

        .text-soft {
            color: var(--text-soft);
        }

        .movie-link {
            text-decoration: none;
            color: inherit;
        }

        .hero-explore {
            background: linear-gradient(135deg, rgba(76, 70, 229, .22), rgba(16, 22, 47, .85));
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 1rem;
        }

        .featured-card {
            position: relative;
            min-height: 390px;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .1);
            background: #101523;
        }

        .featured-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(.58);
        }

        .featured-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: flex-end;
            padding: 1.25rem;
            background: linear-gradient(0deg, rgba(0, 0, 0, .7), rgba(0, 0, 0, .08));
        }

        .netflix-row {
            display: flex;
            gap: .9rem;
            overflow-x: auto;
            padding-bottom: .35rem;
            scroll-snap-type: x mandatory;
        }

        .netflix-row::-webkit-scrollbar {
            height: 8px;
        }

        .netflix-row::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .18);
            border-radius: 999px;
        }

        .poster-tile {
            width: 185px;
            min-width: 185px;
            scroll-snap-align: start;
            background: var(--bg-surface);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: .9rem;
            overflow: hidden;
            transition: transform .2s ease;
        }

        .poster-tile:hover {
            transform: translateY(-4px);
        }

        .poster-tile img {
            width: 100%;
            height: 255px;
            object-fit: cover;
            background: #1f243a;
        }

        .result-card {
            background: var(--bg-surface);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 1rem;
            overflow: hidden;
            height: 100%;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .movie-link:hover .result-card {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, .35);
        }

        .result-card img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            background: #1f243a;
        }

        .offcanvas {
            background: #101523;
            color: #f8f9fa;
        }

        .mini-stat {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: .8rem;
            padding: .75rem;
        }

        .genre-chip {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #f8f9fa;
            border-radius: 999px;
            text-decoration: none;
            padding: .35rem .8rem;
            font-size: .82rem;
            display: inline-flex;
            align-items: center;
        }

        .genre-chip:hover {
            background: rgba(13, 202, 240, .14);
            border-color: rgba(13, 202, 240, .55);
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="container pt-4 pb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">{{ $isExplore ? 'Explorar' : 'Resultados de búsqueda' }}</h1>
                <p class="mb-0 text-soft">
                    @if ($isExplore)
                        Recomendaciones personalizables estilo catálogo streaming.
                    @elseif($query !== '')
                        Mostrando resultados para: <strong class="text-light">{{ $query }}</strong>
                    @else
                        Escribe algo en el buscador para consultar películas.
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2">
                @if ($isExplore)
                    <button class="btn btn-outline-info" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#exploreFilters" aria-controls="exploreFilters">
                        Filtrar recomendaciones
                    </button>
                @endif
                <a href="{{ url('/') }}" class="btn btn-outline-light">Volver al inicio</a>
            </div>
        </div>

        @if ($error)
            <div class="alert alert-warning" role="alert">
                {{ $error }}
            </div>
        @endif

        @if ($query !== '' && empty($movies))
            <div class="alert alert-secondary" role="alert">
                No se encontraron películas para tu búsqueda.
            </div>
        @endif

        @if ($isExplore)
            @php
                $collection = collect($movies);
                $topRated = $collection
                    ->sortByDesc(fn($movie) => (float) ($movie['vote_average'] ?? 0))
                    ->take(12)
                    ->values();
                $latest = $collection->sortByDesc(fn($movie) => $movie['release_date'] ?? '')->take(12)->values();
                $mostVoted = $collection
                    ->sortByDesc(fn($movie) => (int) ($movie['vote_count'] ?? 0))
                    ->take(12)
                    ->values();
                $popularGenres = collect($genres)->take(10);
            @endphp

            <section class="hero-explore p-3 p-md-4 mb-4">
                @if (!empty($movies))
                    @php
                        $heroMovie = $movies[0];
                        $heroPosterPath = $heroMovie['backdrop_path'] ?? ($heroMovie['poster_path'] ?? null);
                        $heroImage = $heroPosterPath
                            ? 'https://image.tmdb.org/t/p/original' . $heroPosterPath
                            : 'https://via.placeholder.com/1280x720?text=Sin+imagen';
                        $heroYear = !empty($heroMovie['release_date'])
                            ? substr($heroMovie['release_date'], 0, 4)
                            : 'N/D';
                    @endphp
                    <a class="movie-link" href="{{ route('movies.detail', ['id' => $heroMovie['id']]) }}">
                        <article class="featured-card">
                            <img src="{{ $heroImage }}" alt="{{ $heroMovie['title'] ?? 'Película recomendada' }}">
                            <div class="featured-overlay">
                                <div>
                                    <p class="mb-2 small text-soft">Recomendada para ti • {{ $heroYear }}</p>
                                    <h2 class="h3 fw-bold mb-2">{{ $heroMovie['title'] ?? 'Sin título' }}</h2>
                                    <p class="mb-0 text-soft">
                                        {{ \Illuminate\Support\Str::limit($heroMovie['overview'] ?? 'Sin descripción disponible.', 160) }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    </a>
                @else
                    <div class="alert alert-secondary mb-0">No hay recomendaciones con esos filtros todavía.</div>
                @endif
            </section>

            @if (!empty($movies))
                <section class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <article class="mini-stat h-100">
                                <p class="text-soft small mb-1">Catálogo cargado</p>
                                <h2 class="h5 mb-0">{{ count($movies) }} recomendaciones</h2>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="mini-stat h-100">
                                <p class="text-soft small mb-1">Filtro actual</p>
                                <h2 class="h6 mb-0">
                                    {{ $selectedYear !== '' ? 'Año ' . $selectedYear : 'Sin año específico' }}</h2>
                            </article>
                        </div>
                        <div class="col-md-4">
                            <article class="mini-stat h-100">
                                <p class="text-soft small mb-1">Orden</p>
                                <h2 class="h6 mb-0">{{ str_replace('.desc', '', $selectedSort) }}</h2>
                            </article>
                        </div>
                    </div>
                </section>

                @if ($popularGenres->isNotEmpty())
                    <section class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 mb-0">Explorar por género</h2>
                            <small class="text-soft">Acceso rápido</small>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($popularGenres as $genre)
                                <a class="genre-chip"
                                    href="{{ route('search.index', ['q' => '', 'genre' => $genre['id'], 'sort' => $selectedSort, 'year' => $selectedYear]) }}">
                                    {{ $genre['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif

            @if (!empty($movies))
                <section class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 mb-0">Porque te puede gustar</h2>
                        <small class="text-soft">Desliza para explorar</small>
                    </div>

                    <div class="netflix-row">
                        @foreach ($movies as $movie)
                            @php
                                $posterPath = $movie['poster_path'] ?? null;
                                $posterUrl = $posterPath
                                    ? 'https://image.tmdb.org/t/p/w500' . $posterPath
                                    : 'https://via.placeholder.com/500x750?text=Sin+poster';
                            @endphp
                            <a class="movie-link" href="{{ route('movies.detail', ['id' => $movie['id']]) }}">
                                <article class="poster-tile">
                                    <img src="{{ $posterUrl }}" alt="{{ $movie['title'] ?? 'Película' }}">
                                    <div class="p-2">
                                        <p class="small mb-0">
                                            {{ \Illuminate\Support\Str::limit($movie['title'] ?? 'Sin título', 30) }}
                                        </p>
                                    </div>
                                </article>
                            </a>
                        @endforeach
                    </div>
                </section>

                @if ($topRated->isNotEmpty())
                    <section class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 mb-0">Top mejor valoradas</h2>
                            <small class="text-soft">Según puntuación TMDB</small>
                        </div>
                        <div class="netflix-row">
                            @foreach ($topRated as $movie)
                                @php
                                    $posterPath = $movie['poster_path'] ?? null;
                                    $posterUrl = $posterPath
                                        ? 'https://image.tmdb.org/t/p/w500' . $posterPath
                                        : 'https://via.placeholder.com/500x750?text=Sin+poster';
                                @endphp
                                <a class="movie-link" href="{{ route('movies.detail', ['id' => $movie['id']]) }}">
                                    <article class="poster-tile">
                                        <img src="{{ $posterUrl }}" alt="{{ $movie['title'] ?? 'Película' }}">
                                        <div class="p-2">
                                            <p class="small mb-1">
                                                {{ \Illuminate\Support\Str::limit($movie['title'] ?? 'Sin título', 30) }}
                                            </p>
                                            <small class="text-soft">⭐
                                                {{ number_format((float) ($movie['vote_average'] ?? 0), 1) }}</small>
                                        </div>
                                    </article>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($latest->isNotEmpty())
                    <section class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 mb-0">Estrenos recientes</h2>
                            <small class="text-soft">Últimas fechas de lanzamiento</small>
                        </div>
                        <div class="netflix-row">
                            @foreach ($latest as $movie)
                                @php
                                    $posterPath = $movie['poster_path'] ?? null;
                                    $posterUrl = $posterPath
                                        ? 'https://image.tmdb.org/t/p/w500' . $posterPath
                                        : 'https://via.placeholder.com/500x750?text=Sin+poster';
                                    $year = !empty($movie['release_date'])
                                        ? substr($movie['release_date'], 0, 4)
                                        : 'N/D';
                                @endphp
                                <a class="movie-link" href="{{ route('movies.detail', ['id' => $movie['id']]) }}">
                                    <article class="poster-tile">
                                        <img src="{{ $posterUrl }}" alt="{{ $movie['title'] ?? 'Película' }}">
                                        <div class="p-2">
                                            <p class="small mb-1">
                                                {{ \Illuminate\Support\Str::limit($movie['title'] ?? 'Sin título', 30) }}
                                            </p>
                                            <small class="text-soft">📅 {{ $year }}</small>
                                        </div>
                                    </article>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($mostVoted->isNotEmpty())
                    <section class="mb-4" id="mas-comentadas-comunidad">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 mb-0">Más comentadas por la comunidad</h2>
                            <small class="text-soft">Mayor número de votos</small>
                        </div>
                        <div class="netflix-row">
                            @foreach ($mostVoted as $movie)
                                @php
                                    $posterPath = $movie['poster_path'] ?? null;
                                    $posterUrl = $posterPath
                                        ? 'https://image.tmdb.org/t/p/w500' . $posterPath
                                        : 'https://via.placeholder.com/500x750?text=Sin+poster';
                                @endphp
                                <a class="movie-link" href="{{ route('movies.detail', ['id' => $movie['id']]) }}">
                                    <article class="poster-tile">
                                        <img src="{{ $posterUrl }}" alt="{{ $movie['title'] ?? 'Película' }}">
                                        <div class="p-2">
                                            <p class="small mb-1">
                                                {{ \Illuminate\Support\Str::limit($movie['title'] ?? 'Sin título', 30) }}
                                            </p>
                                            <small class="text-soft">👥
                                                {{ number_format((int) ($movie['vote_count'] ?? 0)) }} votos</small>
                                        </div>
                                    </article>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif
        @else
            <div class="row g-3 g-md-4">
                @foreach ($movies as $movie)
                    @php
                        $posterPath = $movie['poster_path'] ?? null;
                        $posterUrl = $posterPath
                            ? 'https://image.tmdb.org/t/p/w500' . $posterPath
                            : 'https://via.placeholder.com/500x750?text=Sin+poster';
                        $releaseYear = !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : 'N/D';
                    @endphp

                    <div class="col-6 col-md-4 col-lg-3">
                        <a class="movie-link" href="{{ route('movies.detail', ['id' => $movie['id']]) }}">
                            <article class="result-card">
                                <img src="{{ $posterUrl }}" alt="{{ $movie['title'] ?? 'Película' }}">
                                <div class="p-3">
                                    <h2 class="h6 mb-1">{{ $movie['title'] ?? 'Sin título' }}</h2>
                                    <p class="small text-soft mb-2">Año: {{ $releaseYear }}</p>
                                    <p class="small text-soft mb-0">
                                        {{ \Illuminate\Support\Str::limit($movie['overview'] ?? 'Sin descripción disponible.', 120) }}
                                    </p>
                                </div>
                            </article>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="exploreFilters" aria-labelledby="exploreFiltersLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title h5" id="exploreFiltersLabel">Filtrar recomendaciones</h2>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="GET" action="{{ route('search.index') }}" class="d-grid gap-3">
                <input type="hidden" name="q" value="">

                <div>
                    <label for="genre" class="form-label">Género</label>
                    <select id="genre" name="genre" class="form-select">
                        <option value="">Todos</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre['id'] }}" @selected((string) $genre['id'] === (string) $selectedGenre)>
                                {{ $genre['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="year" class="form-label">Año</label>
                    <input id="year" type="number" name="year" class="form-control" min="1900"
                        max="2100" value="{{ $selectedYear }}" placeholder="Ej: 2024">
                </div>

                <div>
                    <label for="sort" class="form-label">Ordenar por</label>
                    <select id="sort" name="sort" class="form-select">
                        <option value="popularity.desc" @selected($selectedSort === 'popularity.desc')>Popularidad</option>
                        <option value="vote_average.desc" @selected($selectedSort === 'vote_average.desc')>Mejor puntuadas
                        </option>
                        <option value="release_date.desc" @selected($selectedSort === 'release_date.desc')>Más recientes</option>
                        <option value="revenue.desc" @selected($selectedSort === 'revenue.desc')>Más taquilleras</option>
                    </select>
                </div>

                <div class="d-grid gap-2 mt-2">
                    <button type="submit" class="btn btn-info">Aplicar filtros</button>
                    <a href="{{ route('search.index', ['q' => '']) }}" class="btn btn-outline-light">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
