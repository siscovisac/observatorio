<?php

namespace App\Http\Controllers;

use App\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $rol=Rol::all();
        return $rol;
    }

    public function selectRol(Request $request){
        if (!$request->ajax()) return redirect('/');
        $rol = Rol::select('id','nombre')->orderBy('nombre', 'asc')->get();
        return ['rol' => $rol];
    }

}
