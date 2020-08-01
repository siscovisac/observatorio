<?php

namespace App\Http\Controllers;

use App\Entidad;
use Illuminate\Http\Request;

class EntidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $entidad=Entidad::select('id','nombre','descripcion','direccion','origen','estado')->get();
        return $entidad;
    }

    public function selectEntidad(Request $request){
        if (!$request->ajax()) return redirect('/');
        $entidad = Entidad::select('id','nombre')->where('estado','=',1)->orderBy('nombre', 'asc')->get();
        return ['entidad' => $entidad];
    }
    
    public function selectEntidadOrigen(Request $request){
        if (!$request->ajax()) return redirect('/');
        $origen=$request->origen;
        $entidad = Entidad::select('id','nombre')->where('origen','=',$origen)->where('id','>',1)->where('estado','=',1)->orderBy('nombre', 'asc')->get();
        return ['entidad' => $entidad];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');  
        $request->validate([
            'nombre' => 'bail|required|min:5|max:45|unique:entidads',
            'descripcion'=>'nullable|max:145',
            'direccion'=>'nullable|max:145',
            'origen'=>'required'
        ]);
        $entidad=new Entidad();
        $entidad->nombre=$request->nombre;
        $entidad->descripcion=$request->descripcion;
        $entidad->direccion=$request->direccion;
        $entidad->origen=$request->origen;
        $entidad->estado=$request='1';
        $entidad->user_id=\Auth::user()->id;
        $entidad->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entidad  $entidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entidad $entidad)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'nombre' => ['required', 'max:50', 'unique:entidads,nombre,'.request()->get("id")],
            'descripcion'=>'nullable|max:250',
            'direccion'=>'nullable|max:145',
            'origen'=>'required'
        ]);
        $entidad=Entidad::findOrFail($request->id);
        $entidad->nombre=$request->nombre;
        $entidad->descripcion=$request->descripcion;
        $entidad->direccion=$request->direccion;
        $entidad->origen=$request->origen;
        $entidad->estado=$request='1';
        $entidad->user_id=\Auth::user()->id;
        $entidad->save();
    }

    public function activar(Request $request, Entidad $entidad)
    {
        if (!$request->ajax()) return redirect('/');
        $entidad=Entidad::findOrFail($request->id);
        $entidad->user_id=\Auth::user()->id;
        $entidad->estado=$request='1';
        $entidad->save();
    }
    public function desactivar(Request $request, Entidad $entidad)
    {
        if (!$request->ajax()) return redirect('/');
        $entidad=Entidad::findOrFail($request->id);
        $entidad->estado=$request='0';
        $entidad->user_id=\Auth::user()->id;
        $entidad->save();
    }

}
