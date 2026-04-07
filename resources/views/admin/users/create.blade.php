<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Crear usuario</title>

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
                <h1 class="h4 mb-0">Crear usuario</h1>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light btn-sm">Volver</a>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Nombre</label>
                        <input class="form-control" id="name" name="name" type="text"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" name="email" type="email"
                            value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="phone">Teléfono</label>
                        <input class="form-control" id="phone" name="phone" type="text"
                            value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin"
                                value="1" @checked(old('is_admin'))>
                            <label class="form-check-label" for="is_admin">Administrador</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password">Contraseña</label>
                        <input class="form-control" id="password" name="password" type="password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation"
                            type="password" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary" type="submit">Guardar usuario</button>
                </div>
            </form>
        </section>
    </main>

    @include('partials.footer')
</body>

</html>
