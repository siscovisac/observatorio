<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleMovil extends Model
{
    protected $fillable=['registroOcurrencia_id','movil_id'];
    public $timestamps = false;
    public function registroOcurrencia(){
        return $this->belongsTo('App\RegistroOcurrencia');
    }
    public function unidadMovil(){
        return $this->belongsTo('App\UnidadMovil');
    }
}
