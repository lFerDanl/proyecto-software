<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MaterialDidactico extends Model
{
    use HasFactory;
    protected $table = 'material_didactico';
    protected $primaryKey = 'id';

    protected $fillable = ['descripcion', 'archivo', 'tipo', 'curso_id'];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

}
