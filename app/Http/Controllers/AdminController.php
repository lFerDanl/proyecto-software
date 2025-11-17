<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Models\Rol;
use App\Models\Renovacion;
use TCPDF;
class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
/*usuarios */
    public function usuarios()
    {

        $usuarios = Usuario::all();
        return view('admin.secciones.usuarios',compact('usuarios'));
    }

    public function store(Request $request)
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


            // Redirigir al usuario al dashboard
            return redirect()->route('admin.secciones.usuarios')->with('success', 'Usuario actualizado correctamente');

        } catch (Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al registrar el usuario',
            ], 500);
        }


    }
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $usuario->id,
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $usuario->password,
        ]);

        return redirect()->route('admin.secciones.usuarios')->with('success', 'Usuario actualizado correctamente');
    }
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return redirect()->route('admin.secciones.usuarios')->with('success', 'Usuario eliminado correctamente');
    }



/*renovacion */

    public function renovaciones()
    {
        $renovaciones = Renovacion::with('subscripcion')->get();
        return view('admin.secciones.renovaciones',compact('renovaciones'));
    }





    public function subscripciones()
    {
        return view('admin.secciones.subscripciones');
    }




    public function descargarPDF()
    {
        // Crear una instancia de TCPDF
        $pdf = new TCPDF();

        // Establecer los metadatos del documento
        $pdf->SetCreator('TuAplicacion');
        $pdf->SetAuthor('TuNombre');
        $pdf->SetTitle('Lista de Usuarios');
        $pdf->SetSubject('Usuarios registrados');

        // Configurar las márgenes
        $pdf->SetMargins(10, 10, 10);

        // Añadir una página
        $pdf->AddPage();

        // Obtener los datos que quieres mostrar
        $usuarios = Usuario::all();

        // Construir el contenido HTML del PDF
        $html = '<h1>Lista de Usuarios</h1>';
        $html .= '<table border="1" cellspacing="3" cellpadding="4">';
        $html .= '<thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                    </tr>
                </thead>';
        $html .= '<tbody>';
        foreach ($usuarios as $usuario) {
            $html .= '<tr>
                        <td>' . $usuario->id . '</td>
                        <td>' . $usuario->nombre . '</td>
                        <td>' . $usuario->email . '</td>
                    </tr>';
        }
        $html .= '</tbody></table>';

        // Escribir el contenido HTML en el PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Descargar el PDF
        $pdf->Output('usuarios.pdf', 'D'); // 'D' fuerza la descarga
    }
}
