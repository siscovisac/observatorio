<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class DetallePersonal extends Model
{
    protected $fillable=['registroOcurrencia_id','personal_id'];
    public $timestamps = false;
    public function registroOcurrencia(){
        return $this->belongsTo('App\RegistroOcurrencia');
    }
    public function personalServicio(){
        return $this->belongsTo('App\PersonalServicio');
    }
}
