<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertaLoginCorreo;

use Illuminate\Support\Facades\Cache;

class EnviarCorreo
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        //Obtener la informacion del usuario en cuanto inicia sesión
        $user = $event->user;

        //Registrar el envio del correo en cache
        $registro = 'login_' . $user->id;

        //Evaluar si el usuario ya recibio un correo
        if (cache::has($registro)) {
            return;
        }

        #Registrar envio de corre en cache y borrarlo despues de 10 segundos
        Cache::put($registro, true, now()->addSecond(10));

        Mail::to($user->email)->send(new AlertaLoginCorreo($user));
    }
}
