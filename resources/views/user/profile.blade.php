<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mi perfil | YAAM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background: #090b14;
            color: #f8f9fa;
        }

        .data-card {
            background: #11172b;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 1rem;
        }

        .quick-card {
            background: #12172a;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: .9rem;
            height: 100%;
        }

        .movie-card,
        .comment-card {
            background: #12172a;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: .9rem;
            height: 100%;
        }

        .movie-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: .7rem;
        }

        .text-soft {
            color: #a7acc0;
        }

        .movie-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="container py-4 py-md-5">
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="data-card p-4 mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <h1 class="h4 mb-2">Mi perfil</h1>
                    <p class="text-soft mb-3">Panel general de tu cuenta.</p>
                    <ul class="list-unstyled mb-0 text-soft">
                        <li><strong class="text-light">Nombre:</strong> {{ $user->name }}</li>
                        <li><strong class="text-light">Correo:</strong> {{ $user->email }}</li>
                        <li><strong class="text-light">Teléfono:</strong> {{ $user->phone ?: 'Sin registrar' }}</li>
                        <li><strong class="text-light">Rol:</strong> {{ $user->is_admin ? 'Administrador' : 'Usuario' }}
                        </li>
                    </ul>
                </div>

                <div class="d-grid gap-2 align-content-start" style="min-width: 230px;">
                    <a href="{{ route('user.editProfile') }}" class="btn btn-primary">Editar perfil</a>
                    <a href="{{ route('user.favorites') }}" class="btn btn-outline-light">Ver mis favoritos</a>
                    <a href="{{ route('user.comments') }}" class="btn btn-outline-light">Ver mis comentarios</a>
                    @if ($user->is_admin)
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-warning">Panel admin
                            (usuarios)</a>
                    @endif
                </div>
            </div>
        </section>

        <section class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <article class="quick-card p-3">
                        <p class="text-soft small mb-1">Resumen</p>
                        <h2 class="h5 mb-1">{{ $favoritesCount }} películas guardadas</h2>
                        <p class="mb-0 text-soft">Repartidas en tus listas personalizadas.</p>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="quick-card p-3">
                        <p class="text-soft small mb-1">Resumen</p>
                        <h2 class="h5 mb-1">{{ $commentsCount }} comentarios</h2>
                        <p class="mb-0 text-soft">Opiniones publicadas por ti.</p>
                    </article>
                </div>
                <div class="col-md-12">
                    <article class="quick-card p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-soft small mb-1">Notificaciones</p>
                            <h2 class="h5 mb-1">{{ $unreadNotificationsCount }} sin leer</h2>
                            <p class="mb-0 text-soft">Tienes notificaciones nuevas. Ir a notificaciones para verlas.</p>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-info btn-sm">Ir a
                            notificaciones</a>
                    </article>
                </div>
            </div>
        </section>

        <section class="data-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Últimos guardados</h2>
                <a href="{{ route('user.favorites') }}" class="btn btn-outline-light btn-sm">Ver todos</a>
            </div>

            @if ($favorites->isEmpty())
                <p class="text-soft mb-0">Aún no has guardado películas en listas.</p>
            @else
                <div class="row g-3">
                    @foreach ($favorites->take(6) as $favorite)
                        @php
                            $movie = $favorite->movie;
                            $posterUrl =
                                $movie && $movie->posterPath
                                    ? 'https://image.tmdb.org/t/p/w500' . $movie->posterPath
                                    : 'https://via.placeholder.com/500x750?text=Sin+poster';
                        @endphp

                        @if ($movie)
                            <div class="col-6 col-md-4 col-lg-2">
                                <a class="movie-link"
                                    href="{{ route('movies.detail', ['id' => $movie->external_id]) }}">
                                    <article class="movie-card p-2">
                                        <img src="{{ $posterUrl }}" alt="{{ $movie->title }}">
                                        <p class="small mb-0 mt-2 text-center">
                                            {{ \Illuminate\Support\Str::limit($movie->title, 28) }}</p>
                                    </article>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </section>

        <section class="data-card p-4">
            <h2 class="h5 mb-3">Mis comentarios</h2>

            @if ($comments->isEmpty())
                <p class="text-soft mb-0">Aún no has comentado películas.</p>
            @else
                <div class="row g-3">
                    @foreach ($comments as $comment)
                        @php
                            $movie = $comment->movie;
                        @endphp
                        <div class="col-12 col-md-6">
                            <article class="comment-card p-3">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                    <h3 class="h6 mb-0">{{ $movie?->title ?? 'Película eliminada' }}</h3>
                                    <small class="text-soft">{{ $comment->created_at?->format('d/m/Y') }}</small>
                                </div>
                                <p class="mb-2 text-soft">{{ $comment->bodyComment }}</p>
                                @if ($movie)
                                    <a href="{{ route('movies.detail', ['id' => $movie->external_id]) }}"
                                        class="link-light small">Ver película</a>
                                @endif
                            </article>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
