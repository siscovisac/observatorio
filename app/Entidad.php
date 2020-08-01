<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    protected $fillable=['nombre','descripcion','direccion','origen','estado', 'user_id'];
    public function registroOcurrencia(){
        return $this->hasMany('App\RegistroOcurrencia');
    }
}
