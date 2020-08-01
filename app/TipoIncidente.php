<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoIncidente extends Model
{
    protected $fillable=['nombre', 'descripcion', 'ordenanza', 'incidente_id', 'estado', 'user_id'];
    
    public function incidente(){
        return $this->belongsTo('App\Incidente');
    }

    public function registroOcurrencia(){
        return $this->hasMany('App\RegistroOcurrencia');
    }
}
