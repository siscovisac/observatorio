<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $fillable=['nombre', 'zona_id', 'estado', 'user_id'];
    public function zona(){
        return $this->belongsTo('App\Zona');
    }
    public function registroOcurrencia(){
        return $this->hasMany('App\RegistroOcurrencia');
    }
}
