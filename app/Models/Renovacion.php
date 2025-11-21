<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renovacion extends Model
{
    use HasFactory;
    protected $table = 'renovaciones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'suscripcion_id',
        'fecha_creacion',
        'estado',
    ];
      // Relación con el modelo Subscripcion
      public function subscripcion()
      {
          return $this->belongsTo(Subscripcion::class, 'subscripcion_id');
      }
}
