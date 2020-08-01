<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuenteImagen extends Model
{
    protected $fillable=['nombre','abreveatura' ,'descripcion', 'estado', 'user_id'];
    
    public function registroOcurrencia(){
        return $this->hasMany('App\RegistroOcurrencia');
    }
}
