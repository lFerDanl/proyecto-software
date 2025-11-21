<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Compra;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $timestamps = false; // Si no tienes campos de timestamp

    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'contrasena',
        'fecha_nacimiento',
        'rol_id'
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

// Relación con el modelo Suscripcion
public function suscripciones()
{
    return $this->hasMany(Suscripcion::class, 'consumidor_id');
}

/**
 * Verifica si el usuario tiene una suscripción activa
 *
 * @return bool
 */
public function tieneSuscripcionActiva()
{
    return $this->suscripciones()
        ->where('estado', true)
        ->where('fecha_fin', '>', \Carbon\Carbon::now())
        ->exists();
}

/**
 * Obtiene la suscripción activa del usuario, si existe
 *
 * @return \App\Models\Suscripcion|null
 */
public function getSuscripcionActiva()
{
    return $this->suscripciones()
        ->where('estado', true)
        ->where('fecha_fin', '>', \Carbon\Carbon::now())
        ->first();
}

}
