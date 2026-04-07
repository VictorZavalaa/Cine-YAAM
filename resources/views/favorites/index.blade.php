<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mis listas favoritas | YAAM</title>

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
            transition: transform .2s ease, box-shadow .2s ease;
            height: 100%;
        }

        .list-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, .35);
        }

        .text-soft {
            color: #a7acc0;
        }

        .badge-count {
            background: rgba(79, 70, 229, .2);
            color: #c7d2fe;
            border: 1px solid rgba(99, 102, 241, .4);
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
                <h1 class="h3 mb-1">Mis listas de favoritos</h1>
                <p class="mb-0 text-soft">Administra tus listas personalizadas y su contenido.</p>
            </div>
            <a href="{{ route('search.index') }}" class="btn btn-outline-light">Buscar más películas</a>
        </div>

        <section class="mb-4">
            <article class="list-card p-3">
                <h2 class="h6 mb-3">Crear nueva lista</h2>
                <form method="POST" action="{{ route('favorites.lists.store') }}" class="row g-2">
                    @csrf
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="nameL" maxlength="100"
                            placeholder="Ej. Películas para el finde" required>
                    </div>
                    <div class="col-md-4 d-grid">
                        <button type="submit" class="btn btn-primary">Crear lista</button>
                    </div>
                </form>
            </article>
        </section>

        @if ($lists->isEmpty())
            <div class="alert alert-secondary" role="alert">
                Aún no tienes listas. Crea una y después guarda películas desde el detalle.
            </div>
        @else
            <div class="row g-3 g-md-4">
                @foreach ($lists as $list)
                    <div class="col-12 col-md-6 col-lg-4">
                        @php
                            $previewItems = $list->items->sortBy('created_at')->take(4);
                        @endphp
                        <article class="list-card p-3 d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <h2 class="h5 mb-1">{{ $list->nameL }}</h2>
                                    <span class="badge badge-count">{{ $list->items_count }} película(s)</span>
                                </div>
                                <small class="text-soft">{{ $list->created_at?->format('d/m/Y') }}</small>
                            </div>

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

                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('favorites.show', ['list' => $list->id]) }}"
                                    class="btn btn-sm btn-outline-light flex-fill">Ver contenido</a>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                    data-bs-target="#editListModal-{{ $list->id }}">
                                    Editar
                                </button>
                                <form method="POST"
                                    action="{{ route('favorites.lists.destroy', ['list' => $list->id]) }}"
                                    onsubmit="return confirm('¿Eliminar esta lista completa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </article>

                        <div class="modal fade" id="editListModal-{{ $list->id }}" tabindex="-1"
                            aria-labelledby="editListModalLabel-{{ $list->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="background:#11172b;color:#f8f9fa;">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title" id="editListModalLabel-{{ $list->id }}">Editar
                                            nombre
                                            de lista</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Cerrar"></button>
                                    </div>
                                    <form method="POST"
                                        action="{{ route('favorites.lists.update', ['list' => $list->id]) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <label for="nameL-{{ $list->id }}" class="form-label">Nombre de la
                                                lista</label>
                                            <input id="nameL-{{ $list->id }}" type="text" name="nameL"
                                                class="form-control" value="{{ $list->nameL }}" maxlength="100"
                                                required>
                                        </div>
                                        <div class="modal-footer border-secondary">
                                            <button type="button" class="btn btn-outline-light"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-warning">Guardar cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
