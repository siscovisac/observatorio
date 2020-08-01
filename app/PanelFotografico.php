<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PanelFotografico extends Model
{
    protected $fillable=['registroOcurrencia_id','fotoOriginal','leyenda'];
    public function registroOcurrencia(){
        return $this->belongsTo('App\RegistroOcurrencia');
    }
}
