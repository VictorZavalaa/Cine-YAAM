<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listas favoritas | YAAM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background: #090b14;
            color: #f8f9fa;
        }

        .list-card {
            background: #12172a;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 1rem;
            height: 100%;
        }

        .text-soft {
            color: #a7acc0;
        }

        .list-preview-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: .4rem;
        }

        .list-preview-item {
            border-radius: .45rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .08);
            background: #1f243a;
            aspect-ratio: 2/3;
        }

        .list-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="container py-4 py-md-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Listas favoritas de {{ $user->name }}</h1>
                <p class="mb-0 text-soft">Tus colecciones personalizadas de películas.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('favorites.index') }}" class="btn btn-outline-primary">Gestionar listas</a>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-light">Volver al perfil</a>
            </div>
        </div>

        @if ($lists->isEmpty())
            <div class="alert alert-secondary" role="alert">
                Aún no tienes listas guardadas.
            </div>
        @else
            <div class="row g-3 g-md-4">
                @foreach ($lists as $list)
                    <div class="col-12 col-md-6 col-lg-4">
                        @php
                            $previewItems = $list->items->sortBy('created_at')->take(4);
                        @endphp
                        <article class="list-card p-3 d-flex flex-column gap-2">
                            <h2 class="h5 mb-0">{{ $list->nameL }}</h2>
                            <p class="small text-soft mb-0">{{ $list->items_count }} película(s)</p>

                            @if ($previewItems->isNotEmpty())
                                <div class="list-preview-grid">
                                    @foreach ($previewItems as $previewItem)
                                        @php
                                            $previewMovie = $previewItem->movie;
                                            $previewPoster =
                                                $previewMovie && $previewMovie->posterPath
                                                    ? 'https://image.tmdb.org/t/p/w500' . $previewMovie->posterPath
                                                    : 'https://via.placeholder.com/500x750?text=Sin+poster';
                                        @endphp
                                        @if ($previewMovie)
                                            <div class="list-preview-item">
                                                <img src="{{ $previewPoster }}" alt="{{ $previewMovie->title }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-auto">
                                <a href="{{ route('favorites.show', ['list' => $list->id]) }}"
                                    class="btn btn-outline-light btn-sm">Ver contenido</a>
                            </div>
                        </article>
                    </div>
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
