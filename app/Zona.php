<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $fillable=['nombre','referencia','user_id','estado'];
    public function ubicacion(){
        return $this->hasMany('App\Hubicacion');
    }

}
