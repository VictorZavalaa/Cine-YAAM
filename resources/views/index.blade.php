<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>YAAM | Catálogo de Películas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        :root {
            --bg-main: #090b14;
            --bg-card: #12172a;
            --accent: #4f46e5;
            --accent-soft: #7c73ff;
            --text-soft: #a7acc0;
        }

        body {
            background: radial-gradient(circle at top, #10162f 0%, var(--bg-main) 42%);
            color: #f8f9fa;
            min-height: 100vh;
        }

        .section-title {
            font-weight: 700;
            letter-spacing: .3px;
        }

        .hero-box {
            background: linear-gradient(135deg, rgba(79, 70, 229, .20), rgba(16, 22, 47, .7));
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 1rem;
            backdrop-filter: blur(4px);
        }

        .hero-badge {
            background: rgba(124, 115, 255, .15);
            color: #cfd2ff;
            border: 1px solid rgba(124, 115, 255, .35);
        }

        .movie-card {
            background: var(--bg-card);
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: 1rem;
            overflow: hidden;
            transition: transform .25s ease, box-shadow .25s ease;
            cursor: pointer;
        }

        .movie-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, .35);
        }

        .movie-card img {
            height: 260px;
            object-fit: cover;
        }

        .text-soft {
            color: var(--text-soft);
        }

        .filter-chip {
            border: 1px solid rgba(255, 255, 255, .16);
            background: rgba(255, 255, 255, .03);
            color: #f8f9fa;
        }

        .carousel-item img {
            height: clamp(280px, 45vw, 520px);
            object-fit: cover;
            filter: brightness(.55);
        }

        .carousel-caption {
            text-align: left;
            right: 8%;
            left: 8%;
            bottom: 3rem;
        }

        .search-wrap {
            position: relative;
        }

        .suggestion-list {
            position: absolute;
            left: 0;
            right: 0;
            top: calc(100% + 6px);
            z-index: 30;
            background: #0f1529;
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: .75rem;
            max-height: 280px;
            overflow-y: auto;
            display: none;
        }

        .suggestion-item {
            width: 100%;
            text-align: left;
            border: 0;
            background: transparent;
            color: #f8f9fa;
            padding: .65rem .85rem;
        }

        .suggestion-item:hover {
            background: rgba(255, 255, 255, .08);
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="container pt-4 pb-5">

        <section class="hero-box p-4 p-md-5 mb-4 mb-md-5">
            <span class="badge rounded-pill hero-badge mb-3">Bienvenido a YAAM</span>
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <h1 class="display-5 fw-bold mb-3">Tu catálogo de películas, en un solo lugar</h1>
                    <p class="lead text-soft mb-4">
                        Descubre estrenos, recomendaciones y tendencias.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-primary px-4" href="{{ route('search.index', ['q' => '']) }}">Explorar
                            catálogo</a>
                        <a class="btn btn-outline-light px-4"
                            href="{{ route('search.index', ['q' => '']) }}#mas-comentadas-comunidad">Ver sugeridas</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card bg-transparent border-light-subtle">
                        <div class="card-body p-3 p-md-4">
                            <h2 class="h5 mb-3 text-white">Busca películas en TMDB</h2>
                            <form action="{{ route('search.index') }}" method="GET" id="heroSearchForm"
                                autocomplete="off">
                                <div class="search-wrap">
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" id="heroSearchInput" name="q"
                                            placeholder="Ej. Minions, Dune, Interstellar..." minlength="2" required>
                                        <button class="btn btn-outline-light" type="submit">Buscar</button>
                                    </div>
                                    <div class="suggestion-list" id="suggestionList" role="listbox"
                                        aria-label="Sugerencias de películas"></div>
                                </div>
                            </form>
                            <small class="text-soft">Al buscar te enviará a la vista de resultados.</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="section-title h3 mb-3">Destacadas de la semana</h2>
            @if ($featuredMovies->isEmpty())
                <div class="alert alert-secondary mb-0">Aún no hay películas guardadas en la base de datos.</div>
            @else
                <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach ($featuredMovies as $featuredIndex => $movie)
                            <button type="button" data-bs-target="#featuredCarousel"
                                data-bs-slide-to="{{ $featuredIndex }}"
                                class="{{ $featuredIndex === 0 ? 'active' : '' }}"
                                aria-current="{{ $featuredIndex === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $featuredIndex + 1 }}"></button>
                        @endforeach
                    </div>

                    <!-- Carousel -->
                    <div class="carousel-inner rounded-4 overflow-hidden border border-light-subtle">
                        @foreach ($featuredMovies as $featuredIndex => $movie)
                            @php
                                $posterPath = $movie->posterPath;
                                $imageUrl = !empty($posterPath)
                                    ? (str_starts_with($posterPath, 'http')
                                        ? $posterPath
                                        : 'https://image.tmdb.org/t/p/original' . $posterPath)
                                    : 'https://via.placeholder.com/1280x720?text=Sin+imagen';
                                $releaseYear = $movie->releaseDate
                                    ? \Illuminate\Support\Carbon::parse($movie->releaseDate)->format('Y')
                                    : 'N/D';
                            @endphp

                            <div class="carousel-item {{ $featuredIndex === 0 ? 'active' : '' }}">
                                <img src="{{ $imageUrl }}" class="d-block w-100" alt="{{ $movie->title }}">
                                <div class="carousel-caption">
                                    <h3 class="fw-bold">{{ $movie->title }}</h3>
                                    <p class="mb-3">{{ \Illuminate\Support\Str::limit($movie->overview, 150) }}</p>
                                    <a class="btn btn-light btn-sm"
                                        href="{{ route('movies.detail', ['id' => $movie->external_id]) }}">Ver detalle
                                        ({{ $releaseYear }})
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            @endif
        </section>

        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title h4 mb-0">Nuevas</h2>
                <a class="link-light text-decoration-none" href="#">Ver todas</a>
            </div>
            <div class="row g-3 g-md-4">
                @forelse ($newMovies as $movie)
                    @php
                        $posterPath = $movie->posterPath;
                        $imageUrl = !empty($posterPath)
                            ? (str_starts_with($posterPath, 'http')
                                ? $posterPath
                                : 'https://image.tmdb.org/t/p/w500' . $posterPath)
                            : 'https://via.placeholder.com/500x750?text=Sin+poster';
                        $releaseYear = $movie->releaseDate
                            ? \Illuminate\Support\Carbon::parse($movie->releaseDate)->format('Y')
                            : 'N/D';
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ route('movies.detail', ['id' => $movie->external_id]) }}"
                            class="text-decoration-none text-reset">
                            <article class="movie-card h-100">
                                <img src="{{ $imageUrl }}" class="w-100" alt="{{ $movie->title }}">
                                <div class="p-3">
                                    <h3 class="h6 mb-1">{{ $movie->title }}</h3>
                                    <p class="small text-soft mb-0">Estreno {{ $releaseYear }}</p>
                                </div>
                            </article>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-secondary mb-0">No hay películas nuevas para mostrar todavía.</div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title h4 mb-0">Sugeridas para ti (según favoritos)</h2>
                <a class="link-light text-decoration-none" href="{{ route('favorites.index') }}">Ver favoritos</a>
            </div>
            <div class="row g-3">
                @forelse ($suggestedMovies as $movie)
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('movies.detail', ['id' => $movie->external_id]) }}"
                            class="filter-chip rounded-pill text-center py-2 px-3 d-block text-decoration-none">
                            {{ \Illuminate\Support\Str::limit($movie->title, 22) }}
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-secondary mb-0">Aún no hay sugerencias locales disponibles.</div>
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        (function() {
            const input = document.getElementById('heroSearchInput');
            const suggestionList = document.getElementById('suggestionList');
            const searchEndpoint = "{{ route('search.suggest') }}";

            if (!input || !suggestionList) {
                return;
            }

            let debounceTimer = null;

            const clearSuggestions = () => {
                suggestionList.innerHTML = '';
                suggestionList.style.display = 'none';
            };

            const renderSuggestions = (items) => {
                if (!items.length) {
                    clearSuggestions();
                    return;
                }

                suggestionList.innerHTML = items.map((item) => {
                    const year = item.year ? ` (${item.year})` : '';
                    return `<button type="button" class="suggestion-item" data-title="${item.title.replace(/"/g, '&quot;')}">${item.title}${year}</button>`;
                }).join('');
                suggestionList.style.display = 'block';
            };

            input.addEventListener('input', () => {
                const query = input.value.trim();

                if (query.length < 2) {
                    clearSuggestions();
                    return;
                }

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(async () => {
                    try {
                        const response = await fetch(
                            `${searchEndpoint}?q=${encodeURIComponent(query)}`);
                        const data = await response.json();
                        renderSuggestions(Array.isArray(data.suggestions) ? data.suggestions : []);
                    } catch (error) {
                        clearSuggestions();
                    }
                }, 280);
            });

            suggestionList.addEventListener('click', (event) => {
                const target = event.target;
                if (!(target instanceof HTMLElement)) {
                    return;
                }

                const title = target.dataset.title;
                if (!title) {
                    return;
                }

                input.value = title;
                clearSuggestions();
                document.getElementById('heroSearchForm')?.submit();
            });

            document.addEventListener('click', (event) => {
                if (!suggestionList.contains(event.target) && event.target !== input) {
                    clearSuggestions();
                }
            });
        })();
    </script>
</body>

</html>
