<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;
use App\Models\Usuario;

class Suscripcion extends Model
{
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'consumidor_id');
    }

}
