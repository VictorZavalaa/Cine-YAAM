<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bienvenido | YAAM</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, #10162f 0%, #090b14 42%);
            color: #f8f9fa;
            overflow-x: hidden;
            position: relative;
        }

        .bg-glow {
            position: fixed;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            filter: blur(60px);
            opacity: .28;
            z-index: 0;
            pointer-events: none;
            animation: floatBlob 11s ease-in-out infinite;
        }

        .bg-glow-1 {
            background: #4f46e5;
            top: -120px;
            left: -80px;
        }

        .bg-glow-2 {
            background: #0dcaf0;
            bottom: -150px;
            right: -100px;
            animation-delay: 1.6s;
        }

        .hero-box {
            background: linear-gradient(135deg, rgba(79, 70, 229, .20), rgba(16, 22, 47, .7));
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 1rem;
            backdrop-filter: blur(6px);
            position: relative;
            z-index: 1;
            animation: fadeUp .7s ease-out both;
        }

        .hero-box::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, .06);
            pointer-events: none;
        }

        .text-soft {
            color: #a7acc0;
        }

        .cta-btn {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, .3);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatBlob {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-14px) scale(1.04);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .bg-glow,
            .hero-box {
                animation: none;
            }

            .cta-btn {
                transition: none;
            }
        }
    </style>
</head>

<body>
    @include('partials.navbar')
    <span class="bg-glow bg-glow-1" aria-hidden="true"></span>
    <span class="bg-glow bg-glow-2" aria-hidden="true"></span>

    <main class="container py-5">
        <section class="hero-box p-4 p-md-5 text-center text-md-start">
            <h1 class="display-5 fw-bold mb-3">Bienvenido a YAAM</h1>
            <p class="lead text-soft mb-4">
                Inicia sesión para explorar películas, comentar, guardar favoritos y recibir notificaciones.
            </p>
            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                <a href="{{ route('login') }}" class="btn btn-primary px-4 cta-btn">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="btn btn-outline-light px-4 cta-btn">Crear cuenta</a>
            </div>
        </section>
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
