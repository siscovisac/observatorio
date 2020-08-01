<?php

namespace App\Http\Controllers;

use App\Generico;
use Illuminate\Http\Request;

class GenericoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $generico=Generico::select('id','nombre','descripcion','estado')->get();
        return $generico;
    }

    public function selectGenerico(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $generico=Generico::select('id','nombre','generico_id')
        ->where('estado','=','1')->get();
        return ['generico' => $generico];
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
            'nombre' => 'bail|required|min:5|max:60|unique:genericos',
            'descripcion'=>'nullable|max:145'
        ]);
        $generico=new Generico();
        $generico->nombre=$request->nombre;
        $generico->descripcion=$request->descripcion;
        $generico->estado=$request='1';
        $generico->user_id=\Auth::user()->id;
        $generico->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Generico  $generico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Generico $generico)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'nombre' => ['required', 'max:50', 'unique:genericos,nombre,'.request()->get("id")],
            'descripcion'=>'nullable|max:145'
        ]);
        $generico=Generico::findOrFail($request->id);
        $generico->nombre=$request->nombre;
        $generico->descripcion=$request->descripcion;
        $generico->estado=$request='1';
        $generico->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $generico=Generico::findOrFail($request->id);
        $generico->estado=$request='1';
        $generico->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $generico=Generico::findOrFail($request->id);
        $generico->estado=$request='0';
        $generico->save();
    }
}
