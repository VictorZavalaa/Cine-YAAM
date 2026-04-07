<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Uso de modelo de Movie
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Consultar movies de la base de datos
     */
    public function index()
    {
        //Obtener los datos del modelo
        $movies = Movie::all();

        return view('movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Guardar datos en la base de datos
     */
    public function store(Request $request)
    {
        //Esquema para enviar datos a la BD
        Movie::create([
            'sourceM' => $request->sourceM,
            'external_id' => $request->external_id,
            'title' => $request->title,
            'overview' => $request->overview,
            'releaseDate' => $request->releaseDate,
            'posterPath' => $request->posterPath
        ]);

        //Enviar al usuario a otra pagina
        return redirect()->route('movies.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        //Retornar la vista con los datos de la movie, aun no se reciben datos aqui
        return view('movies.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
