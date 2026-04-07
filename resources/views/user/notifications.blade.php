<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notificaciones | YAAM</title>

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

        .notification-card {
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
        <section class="data-card p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
                <div>
                    <h1 class="h4 mb-1">Notificaciones</h1>
                    <p class="mb-0 text-soft">Actividad de likes y dislikes sobre tus comentarios.</p>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-light btn-sm">Volver a perfil</a>
            </div>

            @if ($notifications->isEmpty())
                <p class="text-soft mb-0">No tienes notificaciones por ahora.</p>
            @else
                <div class="row g-3">
                    @foreach ($notifications as $notification)
                        <div class="col-12 col-md-6">
                            <article class="notification-card p-3">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <p class="mb-0 fw-semibold">
                                        {{ $notification->fromUser?->name ?? 'Alguien' }}
                                        {{ $notification->typeN === 'like' ? 'reaccionó con like' : 'reaccionó con dislike' }}
                                    </p>
                                    <small
                                        class="text-soft">{{ $notification->created_at?->format('d/m/Y H:i') }}</small>
                                </div>

                                <p class="text-soft small mb-2">
                                    {{ $notification->messageN ?: 'Hubo una reacción en uno de tus comentarios.' }}
                                </p>

                                @if ($notification->comment?->movie)
                                    <a href="{{ route('movies.detail', ['id' => $notification->comment->movie->external_id]) }}"
                                        class="link-light small">Ver película relacionada</a>
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
