<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $fillable=['nombre','descripcion','estado', 'user_id'];
    public function personal(){
        return $this->hasMany('App\Personal');
    }
}
