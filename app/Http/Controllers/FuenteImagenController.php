<?php

namespace App\Http\Controllers;

use App\FuenteImagen;
use Illuminate\Http\Request;

class FuenteImagenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $fuenteImagen=FuenteImagen::select('id','nombre','abreveatura','descripcion','estado')->get();
        return $fuenteImagen;
    }

    public function selectFuenteImagen(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $fuenteImagen=FuenteImagen::select('id','nombre')->where('estado','=',1)->orderBy('id', 'asc')->get();
        return ['fuenteImagen' => $fuenteImagen];
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
            'nombre' => 'bail|required|min:4|max:45|unique:fuente_imagens',
            'abreveatura'=>'nullable|required|max:3',
            'descripcion'=>'nullable|max:145'
        ]);
        $fuenteImagen=new FuenteImagen();
        $fuenteImagen->nombre=$request->nombre;
        $fuenteImagen->abreveatura=$request->abreveatura;
        $fuenteImagen->descripcion=$request->descripcion;
        $fuenteImagen->estado=$request=1;
        $fuenteImagen->user_id=\Auth::user()->id;
        $fuenteImagen->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FuenteImagen  $fuenteImagen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FuenteImagen $fuenteImagen)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'nombre' => ['required','min:4','max:45', 'unique:fuente_imagens,nombre,'.request()->get("id")],
            'abreveatura'=>'nullable|required|max:3',
            'descripcion'=>'nullable|max:145'
        ]);
        $fuenteImagen=FuenteImagen::findOrFail($request->id);
        $fuenteImagen->nombre=$request->nombre;
        $fuenteImagen->abreveatura=$request->abreveatura;
        $fuenteImagen->descripcion=$request->descripcion;
        $fuenteImagen->estado=$request=1;
        $fuenteImagen->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $fuenteImagen=FuenteImagen::findOrFail($request->id);
        $fuenteImagen->estado=$request=1;
        $fuenteImagen->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $fuenteImagen=FuenteImagen::findOrFail($request->id);
        $fuenteImagen->estado=$request=0;
        $fuenteImagen->save();
    }
}
