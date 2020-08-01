<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    protected $fillable=['nombre','logo','encabezadoPagina','piePagina','user_id'];
}
