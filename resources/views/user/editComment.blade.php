<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar comentario | YAAM</title>

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

        .text-soft {
            color: #a7acc0;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="container py-4 py-md-5">
        <section class="data-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 mb-1">Editar comentario</h1>
                    <p class="mb-0 text-soft">
                        {{ $movie?->title ? 'Película: ' . $movie->title : 'Película no disponible' }}
                    </p>
                </div>
                <a href="{{ route('user.comments') }}" class="btn btn-outline-light btn-sm">Volver</a>
            </div>

            <form method="POST" action="{{ route('comments.update', ['comment' => $comment->id]) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="bodyComment" class="form-label">Tu comentario</label>
                    <textarea id="bodyComment" name="bodyComment" class="form-control @error('bodyComment') is-invalid @enderror"
                        rows="6" minlength="3" maxlength="1500" required>{{ old('bodyComment', $comment->bodyComment) }}</textarea>
                    @error('bodyComment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary px-4">Guardar cambios</button>
                </div>
            </form>
        </section>
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
