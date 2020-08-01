<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistroOcurrencia extends Model
{
    protected $casts = [ 'id' => 'int', 'personalServicio' => 'array', 'unidadMovil' => 'array'];
    protected $fillable=[
        'fecha',
        'hora',
        'inicio',
        'telefono',
        'administrado',
        'ocurrencia',
        'tipoIncidente_id',
        'ubicacion_id',
        'referencia',
        'unidadMovil',
        'personalServicio',
        'interviene',
        'parteServicio',
        'llamada',
        'detalleParteServicio',
        'entidad_id',
        'asume',
        'fuenteImagen_id',
        'fin',
        'solucion',
        'estado',
        'user_id'
    ];

    public function tipoIncidente(){
        return $this->belongsTo('App\TipoIncidente');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function ubicacion(){
        return $this->belongsTo('App\Ubicacion');
    }
    public function entidad(){
        return $this->belongsTo('App\Entidad');
    }
    public function fuenteImagen(){
        return $this->belongsTo('App\FuenteImagen');
    }

    public function panelFotografico(){
        return $this->hasMany('App\PanelFotografico');
    }
    public function detalleMovil(){
        return $this->hasMany('App\DetalleMovil');
    }
    public function detallePersonal(){
        return $this->hasMany('App\detallePersonal');
    }
}
