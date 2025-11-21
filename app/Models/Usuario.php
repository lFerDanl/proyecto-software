<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Compra;

class Usuario extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $timestamps = false; // Si no tienes campos de timestamp

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'contrasena',
        'fecha_nacimiento',
        'rol_id',
        'avatar_url',
        'config_preferencias',
        'role',
    ];

    protected $hidden = [
        'contrasena',
    ];

    // Método para indicar a Laravel cuál es el campo de contraseña
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // Relación con el rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
    // Relación con el modelo Compra
    public function compras()
    {
        return $this->hasMany(Compra::class, 'usuario_id');
    }

// Relación con el modelo Calificacion
public function calificaciones()
{
    return $this->hasMany(Calificacion::class, 'usuario_id');
}

}
