<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//Uso de eventos
use Illuminate\Support\Facades\Event;
//Uso de eventos de autenticacion
use Illuminate\Auth\Events\Login;
//Uso de listener para enviar correo
use App\Listeners\EnviarCorreo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Registrar evento para enviar correo de alerta de inicio de sesión
        Event::listen(Login::class, EnviarCorreo::class);
    }
}
