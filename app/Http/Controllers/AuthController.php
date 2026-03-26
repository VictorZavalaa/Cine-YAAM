<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    //Metodo para regresar vista del registro
    public function registerForm()
    {
        return view('auth.register');
    }

    //Meotdo para registrar los usuarios
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required',
            'password' => 'required|confirmed',
        ]);

        //Guardar informacion en la base de datos
        $user = User::create([

            //base de datos --- nombre del formulario
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_admin' => $request->has('is_admin') ? true : false
        ]);

        //Inicio de sesion automatico
        Auth::login($user);

        return redirect()->route('usuarios.index')->with('success', '¡Te has registrado correctamente!');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        //Validar datos en el formulario
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        //Intentar realizar el inicio de sesion con la informacion proporcionada del formulario
        if (Auth::attempt($data)) {
            $request->session()->regenerate();

            //Ruta a la que se redirige despues de iniciar sesion 
            return redirect()->route('movies.index')->with('success', '¡Has iniciado sesión correctamente!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ]);
    }

    //Metodo para cerrar sesion
    public function logout(Request $request)
    {

        //Cerrar sesion
        Auth::logout();

        //Cerrar credenciales del usuario
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        //rederigir a la pagina de inicio de sesion
        return redirect('/acceso')->with('success', '¡Has cerrado sesión correctamente!');
    }


    //Metodo para regresar vista del administrador
    public function adminDashboard()
    {
        return view('admin.admin-dashboard');
    }
}
