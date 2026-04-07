@php
    $alerts = [];

    if (session('status')) {
        $alerts[] = ['icon' => 'success', 'text' => session('status')];
    }

    if (session('success')) {
        $alerts[] = ['icon' => 'success', 'text' => session('success')];
    }

    if (session('error')) {
        $alerts[] = ['icon' => 'error', 'text' => session('error')];
    }

    if (session('warning')) {
        $alerts[] = ['icon' => 'warning', 'text' => session('warning')];
    }

    if (session('info')) {
        $alerts[] = ['icon' => 'info', 'text' => session('info')];
    }

    if ($errors->any()) {
        $alerts[] = ['icon' => 'error', 'text' => $errors->first()];
    }
@endphp

@if (!empty($alerts))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {
            const alerts = @json($alerts);

            alerts.forEach((alert, index) => {
                setTimeout(() => {
                    Swal.fire({
                        icon: alert.icon,
                        text: alert.text,
                        confirmButtonText: 'Entendido',
                        background: '#11172b',
                        color: '#f8f9fa',
                        confirmButtonColor: '#4f46e5'
                    });
                }, index * 150);
            });
        })();
    </script>
@endif
