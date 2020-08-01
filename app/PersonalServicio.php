<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalServicio extends Model
{
    protected $fillable=['apellidos','nombres','dni','fechaNacimiento','direccion','telefono','correo','fechaIngeso','grupo','cargo_id','fechaCese','observacion','estado','user_id'];
    public function cargo(){
        return $this->belongsTo('App\Cargo');
    }
    public function regitroOcurrencia(){
        return $this->hasMany('App\RegistroOcurrencia');
    }
}
