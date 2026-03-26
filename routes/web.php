<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MovieController;

Route::get('/', function () {
    return view('welcome');
});


//Ruta para el dashboard del usuario, protegida por middleware de autenticacion para que solo los usuarios autenticados puedan acceder
Route::middleware(['auth'])->group(function () {

    //Usar los metodos del controlador en la rutas
    Route::resource('movies', MovieController::class);
});



//------------------------------------------------------------

//Ruta para regresar la vista del formulario de registro
Route::get('/registro', [
    AuthController::class,
    'registerForm'
])->name('registro');

//Ruta para registrar los usuarios
Route::post('/registro', [
    AuthController::class,
    'register'
])->name('registro.store');

//Ruta para regresar vista de inicio de sesion
Route::get('/acceso', [
    AuthController::class,
    'loginForm'
])->name('acceso');


//------------------------------------------------------------
//Rutas para el CRUD de usuarios

//Usar el metodo del controlador en la ruta
Route::resource('usuarios', UsuarioController::class);

//Ruta para consultar los usuarios registrados
Route::get('usuario/{id}/edit', [
    UsuarioController::class,
    'edit'
])->name('usuarios.edit');

//Ruta para actualizar la informacion
Route::put('usuario/{id}', [
    UsuarioController::class,
    'update'
])->name('usuarios.update');

//------------------------------------------------------------

//Ruta para iniciar sesion
Route::post('/acceso', [
    AuthController::class,
    'login'
])->name('acceso.store');


//Ruta para cerrar sesion
Route::post('/cerrar', [
    AuthController::class,
    'logout'
])->name('cerrar');

//------------------------------------------------------------

//Ruta para el dashboard del admin, protegida por middleware de autenticacion y admin
route::middleware(['auth', 'admin'])->group(function () {

    route::get('/admin-dashboard', [
        AuthController::class,
        'adminDashboard'
    ])->name('admin-dashboard');
});








// (Rutas redundantes de movies eliminadas porque ya están dentro del middleware 'auth' arriba)
