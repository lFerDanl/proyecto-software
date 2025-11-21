<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $table = 'cursos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'descripcion',
        'autor',
        'categoria_id',
        'precio',
        'tiempo',

        'estado',
        'fecha_creacion',
        'imagen',
    ];

    // Relación con el modelo User
    public function autornombre()
    {
        return $this->belongsTo(Usuario::class, 'autor', 'id');
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
     // Relación con MaterialDidactico
     public function materialesDidacticos()
    {
        return $this->hasMany(MaterialDidactico::class, 'curso_id');
    }
  // Relación con el modelo Compra (usuarios que compraron el curso)
public function compras()
{
    return $this->hasMany(Compra::class, 'curso_id');
}

// Relación con el modelo Calificacion (calificaciones del curso)
public function calificaciones()
{
    return $this->hasMany(Calificacion::class, 'curso_id');
}

// Relación con el modelo PlanEstudio (temas asociados al curso)
public function planEstudio()
{
    return $this->hasMany(PlanEstudio::class, 'curso_id');
}
public function usuarios()
{
    return $this->belongsToMany(Usuario::class, 'compras', 'curso_id', 'usuario_id');
}
public function materiales()
{
    return $this->hasMany(MaterialDidactico::class, 'curso_id'); // Ajusta 'curso_id' si es diferente
}
// Método para calcular el promedio de estrellas
public function promedioCalificaciones()
{
    return $this->calificaciones()->avg('estrellas'); // Promedio de las calificaciones
}

/*administrativas */

public function getCalificacionPromedioAttribute()
{
    return $this->calificaciones()->avg('calificacion') ?: 0;
}

}
