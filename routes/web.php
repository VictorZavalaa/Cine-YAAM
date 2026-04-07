<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentReactionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Models\Movie;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;


//-------- Registro ---------

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }

    return view('welcome');
})->name('welcome');


// Rutas para el registro de usuarios
Route::get('/register', [
    AuthController::class,
    'registerForm'
])->name('register');

// Rutas para el registro de usuarios 
Route::get('/register/create', [
    AuthController::class,
    'registerForm'
])->name('register.create');

// Ruta para manejar el envío del formulario de registro
Route::post('/register', [
    AuthController::class,
    'register'
])->name('register.store');


//-------- Inicio de sesión ---------


// Rutas para inicio de sesión
Route::get('/login', [
    AuthController::class,
    'loginForm'
])->name('login');

Route::post('/login', [
    AuthController::class,
    'login'
])->name('login.store');


// Rutas protegidas por el middleware de verificación de usuario


Route::middleware(['auth'])->group(function () {

    //Ruta para la pagina de inicio para pedir peliculas de la base de datos

    Route::get('/home', function () {
        if (! Schema::hasTable('movie')) {
            /** @var Collection<int, Movie> $featuredMovies */
            $featuredMovies = collect();
            /** @var Collection<int, Movie> $newMovies */
            $newMovies = collect();
            /** @var Collection<int, Movie> $suggestedMovies */
            $suggestedMovies = collect();
        } else {
            $featuredMovies = Movie::query()
                ->latest('created_at')
                ->take(3)
                ->get();

            $newMovies = Movie::query()
                ->orderByDesc('releaseDate')
                ->orderByDesc('created_at')
                ->take(8)
                ->get();

            $suggestedMovies = Movie::query()
                ->withCount('favoriteListItems')
                ->orderByDesc('favorite_list_items_count')
                ->orderByDesc('created_at')
                ->take(6)
                ->get();
        }

        return view('index', [
            'featuredMovies' => $featuredMovies,
            'newMovies' => $newMovies,
            'suggestedMovies' => $suggestedMovies,
        ]);
    })->name('home');


    //Ruta para busqueda de peliculas
    Route::get('/search', [
        SearchController::class,
        'index'
    ])->name('search.index');

    //Ruta para el detalle de la pelicula
    Route::get('/movies/{id}', [
        SearchController::class,
        'detail'
    ])->whereNumber('id')->name('movies.detail');

    // Ruta para las sugerencias de búsqueda
    Route::get('/search/suggest', [
        SearchController::class,
        'suggest'
    ])->name('search.suggest');

    // Ruta para cerrar sesión
    Route::post('/logout', [
        AuthController::class,
        'logout'
    ])->name('logout');

    //--------------- Favoritos ------------------

    //Ruta para mostrar los favoritos del usuario
    Route::get('/favorites', [
        FavoriteController::class,
        'index'
    ])->name('favorites.index');

    //Ruta para mostrar una lista de favoritos específica
    Route::get('/favorites/{list}', [
        FavoriteController::class,
        'show'
    ])->whereNumber('list')->name('favorites.show');

    //Ruta para agregar una película a una lista de favoritos
    Route::post('/favorites', [
        FavoriteController::class,
        'store'
    ])->name('favorites.store');

    // Ruta para crear una lista de favoritos
    Route::post('/favorites/lists', [
        FavoriteController::class,
        'storeList'
    ])->name('favorites.lists.store');

    // Ruta para editar nombre de una lista de favoritos
    Route::put('/favorites/lists/{list}', [
        FavoriteController::class,
        'updateList'
    ])->whereNumber('list')->name('favorites.lists.update');

    // Ruta para eliminar una lista completa de favoritos
    Route::delete('/favorites/lists/{list}', [
        FavoriteController::class,
        'destroy'
    ])->whereNumber('list')->name('favorites.lists.destroy');

    // Ruta para eliminar una película dentro de una lista
    Route::delete('/favorites/lists/{list}/movies/{item}', [
        FavoriteController::class,
        'removeMovie'
    ])->whereNumber('list')->whereNumber('item')->name('favorites.items.destroy');


    //--------------- Comentarios ------------------

    //Ruta para mostrar el formulario de creación de comentario para una película específica
    Route::get('/movie/createComentario/{tmdbId}', [
        CommentController::class,
        'createComentario'
    ])
        ->whereNumber('tmdbId')
        ->name('comments.createComentario');

    //Ruta para manejar el envío del formulario de creación de comentario
    Route::post('/movie/createComentario', [
        CommentController::class,
        'storeComentario'
    ])->name('comments.storeComentario');

    // Ruta para mostrar el formulario de edición de un comentario del usuario autenticado
    Route::get('/user/comments/{comment}/edit', [
        CommentController::class,
        'edit'
    ])->name('comments.edit');

    // Ruta para actualizar un comentario del usuario autenticado
    Route::put('/user/comments/{comment}', [
        CommentController::class,
        'update'
    ])->name('comments.update');

    // Ruta para eliminar un comentario del usuario autenticado
    Route::delete('/user/comments/{comment}', [
        CommentController::class,
        'destroy'
    ])->name('comments.destroy');

    // Ruta para reaccionar a un comentario (me gusta o no me gusta)
    Route::post('/comments/{comment}/reaction', [
        CommentReactionController::class,
        'react'
    ])->name('comments.react');

    //--------------- Perfil de usuario y administración ------------------

    // Ruta para mostrar el perfil del usuario
    Route::get('/profile', [
        ProfileController::class,
        'show'
    ])->name('profile.show');

    // Ruta para mostrar las notificaciones del usuario
    Route::get('/notifications', [
        ProfileController::class,
        'notifications'
    ])->name('notifications.index');

    // Ruta para mostrar los favoritos del usuario
    Route::get('/user/favorites', [
        ProfileController::class,
        'favorites'
    ])->name('user.favorites');

    // Ruta para mostrar los comentarios del usuario
    Route::get('/user/comments', [
        ProfileController::class,
        'comments'
    ])->name('user.comments');

    // Ruta para mostrar el formulario de edición del perfil del usuario
    Route::get('/user/edit-profile', [
        ProfileController::class,
        'edit'
    ])->name('user.editProfile');

    // Ruta para manejar el envío del formulario de edición del perfil del usuario
    Route::put('/profile', [
        ProfileController::class,
        'update'
    ])->name('profile.update');

    // Rutas para el panel de administración (solo accesibles para usuarios con rol de admin)

    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

        //Rutas para mostrar el dashboard del panel de administracion 
        Route::get('/dashboard', [
            AdminUserController::class,
            'index'
        ])->name('dashboard');

        // Rutas para mostrar la lista de usuarios en el panel de administración
        Route::get('/users', [
            AdminUserController::class,
            'index'
        ])->name('users.index');

        // Rutas para la creacion de usuario formulario
        Route::get('/users/create', [
            AdminUserController::class,
            'create'
        ])->name('users.create');

        // Ruta para enviar el formulario de creación de usuario
        Route::post('/users', [
            AdminUserController::class,
            'store'
        ])->name('users.store');

        // Ruta para editar un usuario específico
        Route::get('/users/{user}/edit', [
            AdminUserController::class,
            'edit'
        ])->name('users.edit');

        // Ruta para actualizar un usuario específico
        Route::put('/users/{user}', [
            AdminUserController::class,
            'update'
        ])->name('users.update');

        // Ruta para eliminar un usuario específico
        Route::delete('/users/{user}', [
            AdminUserController::class,
            'destroy'
        ])->name('users.destroy');
    });
});
