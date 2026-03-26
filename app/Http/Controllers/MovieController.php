<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Usar el modelo de movie
use App\Models\movie;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Obtener datos del modelo
        $movies = movie::all();

        //Mandar los datos a la vista index
        return view('movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('movies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Esquema para enviar datos a la DB

        movie::create([
            'sourceM' => $request->sourceM,
            'external_id' => $request->external_id,
            'title' => $request->title,
            'overview' => $request->overview,
            'releaseDate' => $request->releaseDate,
            'posterPath' => $request->posterPath,
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
        return view('movies.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'sourceM' => 'required',
            'external_id' => 'required',
            'title' => 'required',
            'overview' => 'required',
            'releaseDate' => 'required',
            'posterPath' => 'required',
        ]);

        $movie->update($request->all());

        return redirect()->route('movies.index')->with('success', 'Pelicula actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Pelicula eliminada correctamente');
    }
}
