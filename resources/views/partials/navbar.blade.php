<script src="https://kit.fontawesome.com/a87d2324b8.js" crossorigin="anonymous"></script>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top"
    style="background: rgba(9, 11, 20, .92); backdrop-filter: blur(8px); border-bottom: 1px solid rgba(255,255,255,.1);">
    @auth
        @php
            $unreadNavNotifications = \App\Models\CommentNotification::query()
                ->where('idUserN', auth()->id())
                ->where('isReadN', false)
                ->count();
        @endphp
    @endauth
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ auth()->check() ? route('home') : route('welcome') }}">YAAM</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"><i
                                class="fa-regular fa-house"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('search') ? 'active' : '' }}"
                            href="{{ route('search.index', ['q' => '']) }}"><i class="fa-solid fa-magnifying-glass"></i>
                            Explorar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('favorites*') ? 'active' : '' }}"
                            href="{{ route('favorites.index') }}"><i class="fa-regular fa-star"></i> Favoritos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('notifications') ? 'active' : '' }}"
                            href="{{ route('notifications.index') }}">
                            <i class="fa-regular fa-bell"></i> Notificaciones
                            @if ($unreadNavNotifications > 0)
                                <span class="badge text-bg-danger ms-1">{{ $unreadNavNotifications }}</span>
                            @endif
                        </a>
                    </li>
                @endauth
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}"
                            href="{{ route('welcome') }}">Inicio</a>
                    </li>
                @endguest
            </ul>

            @auth
                <form class="d-flex me-lg-3 mb-2 mb-lg-0" action="{{ route('search.index') }}" method="GET"
                    role="search">
                    <input class="form-control form-control-sm me-2" type="search" name="q"
                        placeholder="Buscar película..." aria-label="Buscar" minlength="2" required>
                    <button class="btn btn-outline-light btn-sm" type="submit">Buscar</button>
                </form>
            @endauth

            @guest
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Registro</a>
                </div>
            @endguest

            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ auth()->user()->name }}
                        @if (auth()->user()->is_admin)
                            <span class="badge text-bg-warning ms-1">Admin</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fa-regular fa-user"></i>
                                Mi perfil</a></li>
                        <li><a class="dropdown-item" href="{{ route('notifications.index') }}"><i
                                    class="fa-regular fa-bell"></i> Notificaciones
                                @if ($unreadNavNotifications > 0)
                                    <span class="badge text-bg-danger ms-1">{{ $unreadNavNotifications }}</span>
                                @endif
                            </a></li>
                        <li><a class="dropdown-item" href="{{ route('favorites.index') }}"><i
                                    class="fa-regular fa-star"></i> Mis favoritos</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.comments') }}"><i
                                    class="fa-regular fa-comment"></i> Mis comentarios</a></li>
                        @if (auth()->user()->is_admin && Route::has('admin.dashboard'))
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i
                                        class="fa-solid fa-user-tie" style="color: #efb810;"></i> Panel admin</a></li>
                        @endif
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="px-3">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger w-100"><i
                                        class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>
</nav>

@include('partials.flash-alerts')
