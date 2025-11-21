<?php
namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\MaterialDidactico;
use Illuminate\Http\Request;

class MaterialdidacticoController extends Controller
{
    // Mostrar los materiales de un curso
    public function verMateriales($cursoId)
    {
        $curso = Curso::with('materialesDidacticos')->findOrFail($cursoId);

        return view('client.materiales.cursos-material', compact('curso'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'archivo' => 'required|file|mimes:pdf,mp4,jpg,png',
            'tipo' => 'required|string',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        // Subir el archivo
        $archivoPath = $request->file('archivo')->store('materiales', 'public');

        // Crear el material didáctico
        MaterialDidactico::create([
            'descripcion' => $request->descripcion,
            'archivo' => $archivoPath,
            'tipo' => $request->tipo,
            'curso_id' => $request->curso_id,
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Material didáctico agregado correctamente.');
    }

}
