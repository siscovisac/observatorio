<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email','password','rol_id','estado'
    ];

    public function registroOcurrencia(){
        return $this->hasMany('App\RegistroOcurrencia');
    }
    public function rol(){
        return $this->belongsTo('App\Rol');
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    // Only accept a valid password and 
    // hash a password before saving
    // public function setPasswordAttribute($password)
    // {
    //     if ( $password !== null & $password !== "" )
    //     {
    //         $this->attributes['password'] = bcrypt($password);
    //     }
    // }
}
