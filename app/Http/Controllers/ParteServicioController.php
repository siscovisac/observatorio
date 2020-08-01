<?php

namespace App\Http\Controllers;

use App\ParteServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParteServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $parteServicio=ParteServicio::all()
        ->where('registroOcurrencia_id', '=', $request->registroOcurrencia_id);
        return ['parteServicio'=>$parteServicio];
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
        $path = $request->file('file')->store('parte-servicio/', 'public');
        $parteServicio=new ParteServicio();
        $parteServicio->registroOcurrencia_id=$request->registroOcurrencia_id;
        $parteServicio->parteOriginal=$path;
        $parteServicio->nombreParte=$request->nombreParte;
        $parteServicio->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ParteServicio  $parteServicio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        Storage::disk('public')->delete($request->parteOriginal);
        $parteServicio = ParteServicio::findOrFail($request->id);
        $parteServicio->delete();
    }
}
