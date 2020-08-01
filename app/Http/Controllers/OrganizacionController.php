<?php

namespace App\Http\Controllers;

use App\Organizacion;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Storage;
class OrganizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        return $organizacion = Organizacion::get();
    }

    public function muestraOrganizacion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $organizacion = Organizacion::get();
        return view('principal.headerAdministrador', ['organizacion'=>$organizacion]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Organizacion  $organizacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organizacion $organizacion)
    {
        if (!$request->ajax()) return redirect('/');
        $extraeCaracteres=substr($request->logoAnterior,8) ;
        Storage::disk('public')->delete($extraeCaracteres);
        $ruta=$request->file('file')->store('logotipo', 'public');
        $organizacion=Organizacion::findOrFail($request->id);
        $organizacion->nombre=$request->nombre;
        $organizacion->logo='storage/'.$ruta;
        if ($request->encabezadoPagina=='null') {
            $organizacion->encabezadoPagina='';
        }
        else{
            $organizacion->encabezadoPagina=$request->encabezadoPagina;
        }
        if ($request->piePagina=='null') {
            $organizacion->piePagina='';
        }
        else{
            $organizacion->piePagina=$request->piePagina;
        }
        $organizacion->user_id=\Auth::user()->id;
        $organizacion->save();
    }
}