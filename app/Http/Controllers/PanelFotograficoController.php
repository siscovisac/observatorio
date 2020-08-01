<?php

namespace App\Http\Controllers;

use App\PanelFotografico;
use Illuminate\Http\Request;
use App\User;
// use Illuminate\Support\Facades\Response;
// use Intervention\Image\Facades\Image;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class PanelFotograficoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $panelFotografico=PanelFotografico::all()
        ->where('registroOcurrencia_id', '=', $request->registroOcurrencia_id);
        return ['panelFotografico'=>$panelFotografico];
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
        $path = $request->file('file')->store('panel-fotografico/', 'public');
        $detallePanel=new PanelFotografico();
        $detallePanel->registroOcurrencia_id=$request->registroOcurrencia_id;
        $detallePanel->fotoOriginal=$path;
        $detallePanel->leyenda=$request->leyenda;
        $detallePanel->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PanelFotografico  $panelFotografico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        Storage::disk('public')->delete($request->fotoOriginal);
        $detalle = PanelFotografico::findOrFail($request->id);
        $detalle->delete();
    }
}
