<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle de película | YAAM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background: #090b14;
            color: #f8f9fa;
        }

        .hero-backdrop {
            min-height: 50vh;
            background-size: cover;
            background-position: center;
            position: relative;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .12);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(9, 11, 20, .25) 0%, rgba(9, 11, 20, .88) 70%);
        }

        .hero-content {
            position: absolute;
            inset: auto 0 0 0;
            padding: 2rem;
            z-index: 2;
        }

        .poster-card {
            background: #11172b;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 1rem;
            overflow: hidden;
        }

        .poster-card img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        .data-card {
            background: #11172b;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 1rem;
        }

        .text-soft {
            color: #a7acc0;
        }

        .cast-card {
            background: #12172a;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: .8rem;
            padding: .75rem;
            height: 100%;
        }

        .comment-box {
            background: #12172a;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: .9rem;
            padding: 1rem;
        }

        .reaction-btn {
            border: 1px solid rgba(255, 255, 255, .2);
            color: #f8f9fa;
            background: transparent;
        }

        .reaction-btn.active-like {
            background: rgba(25, 135, 84, .2);
            border-color: rgba(25, 135, 84, .75);
        }

        .reaction-btn.active-dislike {
            background: rgba(220, 53, 69, .2);
            border-color: rgba(220, 53, 69, .75);
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="container py-4 py-md-5">
        @if ($error || !$movie)
            <div class="alert alert-warning" role="alert">
                {{ $error ?? 'No se encontró información para esta película.' }}
            </div>
            <a href="{{ route('search.index') }}" class="btn btn-outline-light">Volver a búsqueda</a>
        @else
            @php
                $dbComments = $comments ?? collect();
                $title = $movie['title'] ?? 'Sin título';
                $originalTitle = $movie['original_title'] ?? null;
                $overview = $movie['overview'] ?? 'Sin descripción disponible.';
                $posterPath = $movie['poster_path'] ?? null;
                $backdropPath = $movie['backdrop_path'] ?? null;
                $posterUrl = $posterPath
                    ? 'https://image.tmdb.org/t/p/w500' . $posterPath
                    : 'https://via.placeholder.com/500x750?text=Sin+poster';
                $backdropUrl = $backdropPath
                    ? 'https://image.tmdb.org/t/p/w1280' . $backdropPath
                    : 'https://via.placeholder.com/1280x720?text=Sin+backdrop';
                $releaseDate = $movie['release_date'] ?? null;
                $voteAverage = isset($movie['vote_average']) ? number_format((float) $movie['vote_average'], 1) : 'N/D';
                $voteCount = $movie['vote_count'] ?? 0;
                $runtime = $movie['runtime'] ?? null;
                $genres = collect($movie['genres'] ?? [])
                    ->pluck('name')
                    ->filter()
                    ->implode(', ');
                $companies = collect($movie['production_companies'] ?? [])
                    ->pluck('name')
                    ->filter()
                    ->take(4)
                    ->implode(', ');
                $countries = collect($movie['production_countries'] ?? [])
                    ->pluck('name')
                    ->filter()
                    ->implode(', ');
                $spokenLanguages = collect($movie['spoken_languages'] ?? [])
                    ->pluck('english_name')
                    ->filter()
                    ->implode(', ');
                $cast = collect($movie['credits']['cast'] ?? [])->take(8);
                $trailer = collect($movie['videos']['results'] ?? [])->first(function ($video) {
                    return ($video['site'] ?? '') === 'YouTube' && ($video['type'] ?? '') === 'Trailer';
                });
                $trailerUrl = $trailer ? 'https://www.youtube.com/watch?v=' . ($trailer['key'] ?? '') : null;
            @endphp

            <section class="hero-backdrop mb-4" style="background-image: url('{{ $backdropUrl }}');">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <h1 class="display-6 fw-bold mb-2">{{ $title }}</h1>
                    @if ($movie['tagline'] ?? false)
                        <p class="mb-2 text-light-emphasis fst-italic">“{{ $movie['tagline'] }}”</p>
                    @endif
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge text-bg-light">⭐ {{ $voteAverage }} / 10</span>
                        <span class="badge text-bg-secondary">{{ $voteCount }} votos</span>
                        @if ($runtime)
                            <span class="badge text-bg-dark">{{ $runtime }} min</span>
                        @endif
                        @if ($releaseDate)
                            <span class="badge text-bg-dark">Estreno: {{ $releaseDate }}</span>
                        @endif
                    </div>
                </div>
            </section>

            <section class="row g-4">
                <div class="col-lg-4 col-xl-3">
                    <article class="poster-card">
                        <img src="{{ $posterUrl }}" alt="{{ $title }}">
                    </article>
                    @if ($trailerUrl)
                        <a href="{{ $trailerUrl }}" target="_blank" rel="noopener" class="btn btn-danger w-100 mt-3">
                            Ver tráiler
                        </a>
                    @endif

                    @auth
                        <button type="button" class="btn btn-warning w-100 mt-3" data-bs-toggle="modal"
                            data-bs-target="#favoriteListModal">
                            Agregar a favoritos
                        </button>

                        <a href="{{ route('comments.createComentario', ['tmdbId' => $movie['id']]) }}"
                            class="btn btn-outline-info w-100 mt-2">Dejar comentario</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light w-100 mt-3">Inicia sesión para
                            guardar en favoritos</a>
                    @endauth
                </div>

                <div class="col-lg-8 col-xl-9">
                    <article class="data-card p-4 mb-4">
                        <h2 class="h4 mb-3">Sinopsis</h2>
                        <p class="mb-0 text-soft">{{ $overview }}</p>
                    </article>

                    <article class="data-card p-4 mb-4">
                        <h2 class="h5 mb-3">Información general</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Título original:</strong> {{ $originalTitle ?: 'N/D' }}</p>
                                <p class="mb-1"><strong>Géneros:</strong> {{ $genres ?: 'N/D' }}</p>
                                <p class="mb-1"><strong>Idioma original:</strong>
                                    {{ $movie['original_language'] ?? 'N/D' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Productoras:</strong> {{ $companies ?: 'N/D' }}</p>
                                <p class="mb-1"><strong>Países:</strong> {{ $countries ?: 'N/D' }}</p>
                                <p class="mb-1"><strong>Idiomas hablados:</strong> {{ $spokenLanguages ?: 'N/D' }}
                                </p>
                            </div>
                        </div>
                    </article>

                    <article class="data-card p-4">
                        <h2 class="h5 mb-3">Reparto principal</h2>
                        @if ($cast->isEmpty())
                            <p class="text-soft mb-0">No hay información de reparto disponible.</p>
                        @else
                            <div class="row g-3">
                                @foreach ($cast as $actor)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="cast-card">
                                            <p class="fw-semibold mb-1">{{ $actor['name'] ?? 'N/D' }}</p>
                                            <p class="small text-soft mb-0">
                                                {{ $actor['character'] ?? 'Sin personaje' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </article>
                </div>
            </section>

            <section class="data-card p-4 mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h5 mb-0">Comentarios de la comunidad</h2>
                    <span class="badge text-bg-secondary">{{ $dbComments->count() }} comentario(s)</span>
                </div>

                @if ($dbComments->isEmpty())
                    <p class="text-soft mb-0">Aún no hay comentarios para esta película.</p>
                @else
                    <div class="d-grid gap-3">
                        @foreach ($dbComments as $comment)
                            @php
                                $currentReaction = optional($comment->reactions->first())->typeR;
                            @endphp
                            <article class="comment-box">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <div>
                                        <h3 class="h6 mb-1">{{ $comment->user?->name ?? 'Usuario' }}</h3>
                                        <small
                                            class="text-soft">{{ $comment->created_at?->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>

                                <p class="mb-3 text-soft">{{ $comment->bodyComment }}</p>

                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    @auth
                                        <form method="POST"
                                            action="{{ route('comments.react', ['comment' => $comment->id]) }}">
                                            @csrf
                                            <input type="hidden" name="reaction" value="like">
                                            <input type="hidden" name="tmdb_id" value="{{ $movie['id'] ?? '' }}">
                                            <button type="submit"
                                                class="btn btn-sm reaction-btn {{ $currentReaction === 'like' ? 'active-like' : '' }}">
                                                👍 Like ({{ $comment->likes_count ?? 0 }})
                                            </button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('comments.react', ['comment' => $comment->id]) }}">
                                            @csrf
                                            <input type="hidden" name="reaction" value="dislike">
                                            <input type="hidden" name="tmdb_id" value="{{ $movie['id'] ?? '' }}">
                                            <button type="submit"
                                                class="btn btn-sm reaction-btn {{ $currentReaction === 'dislike' ? 'active-dislike' : '' }}">
                                                👎 Dislike ({{ $comment->dislikes_count ?? 0 }})
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Inicia sesión
                                            para reaccionar</a>
                                    @endauth
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            @auth
                <div class="modal fade" id="favoriteListModal" tabindex="-1" aria-labelledby="favoriteListModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="background:#11172b;color:#f8f9fa;">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title" id="favoriteListModalLabel">Guardar en favoritos</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                            </div>

                            <form method="POST" action="{{ route('favorites.store') }}">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="tmdb_id" value="{{ $movie['id'] ?? '' }}">
                                    <input type="hidden" name="title" value="{{ $title }}">
                                    <input type="hidden" name="overview" value="{{ $overview }}">
                                    <input type="hidden" name="release_date" value="{{ $releaseDate }}">
                                    <input type="hidden" name="poster_path" value="{{ $posterPath }}">
                                    <input type="hidden" name="source" value="tmdb">

                                    @if (($favoriteLists ?? collect())->isNotEmpty())
                                        <div class="mb-3">
                                            <label class="form-label" for="list_id">Elegir lista existente</label>
                                            <select class="form-select" id="list_id" name="list_id">
                                                <option value="">Selecciona una lista</option>
                                                @foreach ($favoriteLists as $list)
                                                    <option value="{{ $list->id }}">{{ $list->nameL }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="mb-1">
                                        <label class="form-label" for="new_list_name">O crear una lista nueva</label>
                                        <input type="text" class="form-control" id="new_list_name"
                                            name="new_list_name" maxlength="100" placeholder="Ej. Ver en fin de semana">
                                    </div>
                                    <small class="text-soft d-block">Si escribes una lista nueva, tendrá prioridad sobre la
                                        selección.</small>
                                </div>
                                <div class="modal-footer border-secondary">
                                    <button type="button" class="btn btn-outline-light"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-warning">Guardar película</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth
        @endif
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
