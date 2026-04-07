<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar perfil | YAAM</title>

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

        <section class="data-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 mb-1">Editar perfil</h1>
                    <p class="mb-0 text-secondary">Actualiza tus datos y contraseña.</p>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-light btn-sm">Volver al perfil</a>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nombre</label>
                        <input id="name" name="name" type="text" class="form-control"
                            value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo</label>
                        <input id="email" name="email" type="email" class="form-control"
                            value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input id="phone" name="phone" type="text" class="form-control"
                            value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Rol</label>
                        <input type="text" class="form-control"
                            value="{{ $user->is_admin ? 'Administrador' : 'Usuario' }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Nueva contraseña (opcional)</label>
                        <input id="password" name="password" type="password" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="form-control">
                    </div>
                </div>

                <div class="mt-4 d-grid d-md-flex justify-content-md-end">
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
