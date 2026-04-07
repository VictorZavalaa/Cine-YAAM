<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use App\Models\User;

class AuthController extends Controller
{

    //Metodo para regresar vista del registro
    public function registerForm(): View
    {
        return view('auth.register');
    }

    //Meotdo para registrar los usuarios
    public function register(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        $currentUser = Auth::user();
        $canAssignAdmin = (bool) ($currentUser?->is_admin ?? false);

        //Guardar informacion en la base de datos
        $user = User::create([

            //base de datos --- nombre del formulario
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_admin' => $canAssignAdmin ? $request->boolean('is_admin') : false,
        ]);

        if (! Auth::check()) {
            //Inicio de sesion automatico
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Registro exitoso. ¡Bienvenido!');
        }

        return redirect()->route('register')->with('success', 'Usuario creado correctamente.');
    }

    public function loginForm(): View
    {
        return view('auth.login');
    }

    //Metodo para iniciar sesion
    public function login(Request $request): RedirectResponse
    {

        //Validar datos en el formulario
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        //Intentar realizar el ini inicio de sesion con la informacion proporcionada del formulario
        if (Auth::attempt($data, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Sesión iniciada correctamente.');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->onlyInput('email');
    }

    //Metodo para cerrar sesion
    public function logout(Request $request): RedirectResponse
    {

        //Cerrar sesion 
        Auth::logout();

        //Cerrar credenciales del usuario
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        //Redirigir a la pagina de inicio
        return redirect()->route('welcome')->with('info', 'Sesión cerrada.');
    }

    //Metodo para regrewsar vista del adminsitrador
    public function adminDashboard(): View
    {
        return view('admin.admin-dashboard');
    }
}
