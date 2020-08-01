<?php

namespace App\Http\Controllers;

use App\UnidadMovil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadMovilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $unidadMovil=UnidadMovil::select('id','nombre','placa','descripcion','estado')->get();
        return $unidadMovil;
    }

    public function selectUnidadMovil()
    {
        $unidadMovil=UnidadMovil::select('id','nombre',DB::raw("CONCAT(nombre,' ',placa) as unidad_movil"))->get();
        return ['unidadMovil' => $unidadMovil];
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
            'nombre' => 'bail|required|min:4|max:45|unique:unidad_movils',
            'placa'=>'nullable|required|alpha_num:7',
            'descripcion'=>'nullable|max:145'
        ]);
        $unidadMovil=new UnidadMovil();
        $unidadMovil->nombre=$request->nombre;
        $unidadMovil->placa=$request->placa;
        $unidadMovil->descripcion=$request->descripcion;
        $unidadMovil->estado=$request=1;
        $unidadMovil->user_id=\Auth::user()->id;
        $unidadMovil->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UnidadMovil  $unidadMovil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UnidadMovil $unidadMovil)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'nombre' => ['required','min:4','max:45', 'unique:unidad_movils,nombre,'.request()->get("id")],
            'placa'=>'nullable|required|alpha_dash:7',
            'descripcion'=>'nullable|max:145'
        ]);
        $unidadMovil=UnidadMovil::findOrFail($request->id);
        $unidadMovil->nombre=$request->nombre;
        $unidadMovil->placa=$request->placa;
        $unidadMovil->descripcion=$request->descripcion;
        $unidadMovil->estado=$request=1;
        $unidadMovil->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $unidadMovil=UnidadMovil::findOrFail($request->id);
        $unidadMovil->estado=$request=1;
        $unidadMovil->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $unidadMovil=UnidadMovil::findOrFail($request->id);
        $unidadMovil->estado=$request=0;
        $unidadMovil->save();
    }
}
