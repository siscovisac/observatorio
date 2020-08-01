<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    protected $fillable=['nombre','descripcion','estado','user_id'];
    public function tipo_incidente(){
        return $this->hasMany('App\TipoIncidente');
    }
}
