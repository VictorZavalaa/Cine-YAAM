<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Usuarios</title>

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

        .table-dark-custom {
            --bs-table-bg: transparent;
            --bs-table-color: #f8f9fa;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="container py-4 py-md-5">
        <section class="data-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h4 mb-1">Panel administrador - Usuarios</h1>
                    <p class="mb-0 text-soft">Gestiona la información de los usuarios del sistema.</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Nuevo usuario</a>
            </div>

            @if ($users->isEmpty())
                <p class="text-soft mb-0">No hay usuarios registrados.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-dark-custom align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Rol</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?: '—' }}</td>
                                    <td>
                                        @if ($user->is_admin)
                                            <span class="badge text-bg-warning">Admin</span>
                                        @else
                                            <span class="badge text-bg-secondary">Usuario</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-sm btn-outline-info">Editar</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="d-inline js-confirm-delete-user" data-name="{{ $user->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">{{ $users->links() }}</div>
            @endif
        </section>
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {
            const forms = document.querySelectorAll('.js-confirm-delete-user');

            forms.forEach((form) => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    const userName = form.dataset.name || 'este usuario';

                    Swal.fire({
                        title: '¿Eliminar usuario?',
                        text: `Vas a eliminar a ${userName}. Esta acción no se puede deshacer.`,
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
        })();
    </script>
</body>

</html>
