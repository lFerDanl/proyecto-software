<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuario; // Importa el modelo de usuario personalizado
use App\Models\Rol;
use Exception;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('correo', 'contrasena');

        if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['contrasena']])) {
                // Autenticación exitosa
                $usuario = Auth::user();
                session(['usuario_id'=> $usuario->id]);
                session(['usuario_nombre'=> $usuario->nombre]);
                // Redireccionar según el rol del usuario
            if ($usuario->rol->nombre === 'admin') {
                return redirect()->intended('/administrador');
            } elseif ($usuario->rol->nombre === 'cliente') {                
                return redirect()->intended('/usuario');
            } elseif ($usuario->rol->nombre === 'editor') {
                return redirect()->intended('/editor/dashboard');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        // Autenticación fallida
        return back()->withErrors(['correo' => 'Credenciales incorrectas.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public function register(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'correo' => 'required|string|email|max:100|unique:usuarios,correo',
            'contrasena' => 'required|string|min:8|confirmed',
            'fecha_nacimiento' => 'required|date',
        ]);

        // Si la validación falla, regresamos con los errores
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Obtener el rol por defecto "cliente"
            $rolCliente = Rol::where('nombre', 'cliente')->first();

            // Crear el usuario con el rol "cliente" por defecto
            $usuario = Usuario::create([
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'correo' => $request->input('correo'),
                'contrasena' => Hash::make($request->input('contrasena')), // Encripta la contraseña
                'fecha_nacimiento' => $request->input('fecha_nacimiento'),
                'rol_id' => $rolCliente->id, // Asignar rol "cliente"
            ]);

            // Autenticar al usuario automáticamente después del registro
            Auth::login($usuario);

            // Redirigir al usuario al dashboard
            return redirect()->intended('/usuario');

        } catch (Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al registrar el usuario',
            ], 500);
        }
    }

}
