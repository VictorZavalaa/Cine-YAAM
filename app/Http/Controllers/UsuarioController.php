<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Usar el modelo de usuario
use App\Models\usuario;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Obtener datos del modelo
        $usuarios = usuario::all();

        //Mandar los datos a la vista index
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Retornar la vista del formulario de registro
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Guardar los datos en la base de datos
     */
    public function store(Request $request)
    {
        //Esquema para enviar datos a la DB

        usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        //Enviar al usuario a otra pagina
        return redirect()->route('usuarios.create');
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
    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([

            'nombre' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario->update($request->all());

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }
}
