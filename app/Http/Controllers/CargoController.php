<?php

namespace App\Http\Controllers;

use App\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $cargo=Cargo::select('id','nombre','descripcion','estado')->get();
        return $cargo;
    }

    public function selectCargo(Request $request){
        if (!$request->ajax()) return redirect('/');
        $cargo = Cargo::where('estado','=','1')
        ->select('id','nombre')->orderBy('nombre', 'asc')->where('estado','=',1)->get();
        return ['cargo' => $cargo];
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
            'nombre' => 'bail|required|min:5|max:45|unique:cargos',
            'descripcion'=>'nullable|max:145'
        ]);
        $cargo=new Cargo();
        $cargo->nombre=$request->nombre;
        $cargo->descripcion=$request->descripcion;
        $cargo->estado=$request='1';
        $cargo->user_id=\Auth::user()->id;
        $cargo->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cargo  $cargo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cargo $cargo)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'nombre' => ['required', 'max:50', 'unique:cargos,nombre,'.request()->get("id")],
            'descripcion'=>'nullable|max:250'
        ]);
        $cargo=Cargo::findOrFail($request->id);
        $cargo->nombre=$request->nombre;
        $cargo->descripcion=$request->descripcion;
        $cargo->estado=$request='1';
        $cargo->user_id=\Auth::user()->id;
        $cargo->save();
    }
    public function activar(Request $request, Cargo $cargo)
    {
        if (!$request->ajax()) return redirect('/');
        $cargo=Cargo::findOrFail($request->id);
        $cargo->user_id=\Auth::user()->id;
        $cargo->estado=$request='1';
        $cargo->save();
    }
    public function desactivar(Request $request, Cargo $cargo)
    {
        if (!$request->ajax()) return redirect('/');
        $cargo=Cargo::findOrFail($request->id);
        $cargo->estado=$request='0';
        $cargo->user_id=\Auth::user()->id;
        $cargo->save();
    }

}
