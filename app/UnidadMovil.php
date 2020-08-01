<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadMovil extends Model
{
    protected $fillable=['nombre','placa', 'descripcion', 'estado', 'user_id'];

    public function registroOcurrencia(){
        return $this->hasMany('App\RegistroOcurrencia');
    }
}
