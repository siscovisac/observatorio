<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Generico extends Model
{
    protected $fillable=['nombre','descripcion','estado','user_id'];
    public function incidente(){
        return $this->hasMany('App\Incidente');
    }
}
