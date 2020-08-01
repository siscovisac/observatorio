<?php

namespace App\Http\Controllers;

use App\Zona;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $zona=Zona::select('id','nombre','referencia','estado')->get();
        return $zona;
    }

    public function selectZona(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $zona=Zona::select('id','nombre')->get();
        return ['zona' => $zona];
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
            'nombre' => 'bail|required|min:3|max:45|unique:zonas',
            'referencia'=>'nullable|max:145'
        ]);
        $zona=new Zona();
        $zona->nombre=$request->nombre;
        $zona->referencia=$request->referencia;
        $zona->estado=$request='1';
        $zona->user_id=\Auth::user()->id;
        $zona->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Zona  $zona
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zona $zona)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'nombre' => ['required', 'max:45', 'unique:zonas,nombre,'.request()->get("id")],
            'referencia'=>'nullable|max:145'
        ]);
        $zona=Zona::findOrFail($request->id);
        $zona->nombre=$request->nombre;
        $zona->referencia=$request->referencia;
        $zona->estado=$request=1;
        $zona->user_id=\Auth::user()->id;
        $zona->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $zona=Zona::findOrFail($request->id);
        $zona->estado=$request=1;
        $zona->user_id=\Auth::user()->id;
        $zona->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $zona=Zona::findOrFail($request->id);
        $zona->estado=$request=0;
        $zona->user_id=\Auth::user()->id;
        $zona->save();
    }
}
