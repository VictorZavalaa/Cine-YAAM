<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mis comentarios | YAAM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background: #090b14;
            color: #f8f9fa;
        }

        .comment-card {
            background: #12172a;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: .9rem;
            height: 100%;
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
                <h1 class="h3 mb-1">Comentarios de {{ $user->name }}</h1>
                <p class="mb-0 text-soft">Todas tus opiniones publicadas.</p>
            </div>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-light">Volver al perfil</a>
        </div>

        @if ($comments->isEmpty())
            <div class="alert alert-secondary" role="alert">
                Aún no has publicado comentarios.
            </div>
        @else
            <div class="row g-3">
                @foreach ($comments as $comment)
                    @php
                        $movie = $comment->movie;
                    @endphp
                    <div class="col-12 col-md-6">
                        <article class="comment-card p-3">
                            <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                <h2 class="h6 mb-0">{{ $movie?->title ?? 'Película eliminada' }}</h2>
                                <small class="text-soft">{{ $comment->created_at?->format('d/m/Y H:i') }}</small>
                            </div>
                            <p class="mb-2 text-soft">{{ $comment->bodyComment }}</p>
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                @if ($movie)
                                    <a href="{{ route('movies.detail', ['id' => $movie->external_id]) }}"
                                        class="link-light small">Ver película</a>
                                @else
                                    <span class="small text-soft">Sin enlace disponible</span>
                                @endif

                                <div class="d-flex gap-2">
                                    <a href="{{ route('comments.edit', ['comment' => $comment->id]) }}"
                                        class="btn btn-sm btn-outline-warning">Editar</a>
                                    <form method="POST"
                                        action="{{ route('comments.destroy', ['comment' => $comment->id]) }}"
                                        class="delete-comment-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                    </form>
                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.delete-comment-form').forEach((form) => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: '¿Eliminar comentario?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc3545',
                    background: '#11172b',
                    color: '#f8f9fa'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
