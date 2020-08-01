<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParteServicio extends Model
{
    protected $fillable=['registroOcurrencia_id','parteOriginal','nombreParte'];
    public function registroOcurrencia(){
        return $this->belongsTo('App\RegistroOcurrencia');
    }
}
