<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $fillable=['nombre','descripcion'];

    public function users(){
        return $this->hasMany('App\User');
    }
}
