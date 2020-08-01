<?php

namespace App\Http\Controllers;

use App\Incidente;
use App\Organizacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\IncidenciaExport;
use Maatwebsite\Excel\Facades\Excel;

class IncidenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $incidente=Incidente::select('id','nombre','descripcion','estado')->get();
        return $incidente;
    }
    
    public function selectIncidente(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $incidente=Incidente::join('genericos','incidentes.generico_id','=','genericos.id')
        ->select('incidentes.*','genericos.id as generico_id')
        ->where('genericos.estado','=','1')
        ->where('incidentes.estado','=','1')->get();
        return ['incidente' => $incidente];
    }

    public function listarIncidentePDF(){
        $incidentes = Incidente::join('genericos','incidentes.generico_id','=','genericos.id')
        ->select('incidentes.*','genericos.nombre as generico_nombre')->orderBy('incidentes.id')->get();
        $cantidad=Incidente::count();
        $organizacion = Organizacion::get();
        $pdf=PDF::loadView('listaPDF.incidentesPDF', compact(['incidentes','cantidad','organizacion']));
        $pdf->setPaper('A4', '');
        // $pdf->setPaper('A4', 'landscape'); 
        return $pdf->stream('Lista-incidencias.pdf');
    }

    public function listarIncidenteExcel(){
        return Excel::download(new IncidenciaExport, 'Tipo_Evento.xlsx');
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
            'nombre' => 'bail|required|min:5|max:45|unique:incidentes',
            'descripcion'=>'nullable|max:145'
        ]);
        $incidente=new Incidente();
        $incidente->nombre=$request->nombre;
        $incidente->descripcion=$request->descripcion;
        $incidente->estado=$request='1';
        $incidente->user_id=\Auth::user()->id;
        $incidente->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Incidente  $incidente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incidente $incidente)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'nombre' => ['required', 'max:45', 'unique:incidentes,nombre,'.request()->get("id")],
            'descripcion'=>'nullable|max:145'
        ]);
        $incidente=Incidente::findOrFail($request->id);
        $incidente->nombre=$request->nombre;
        $incidente->descripcion=$request->descripcion;
        $incidente->estado=$request='1';
        $incidente->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $incidente=Incidente::findOrFail($request->id);
        $incidente->estado=$request='1';
        $incidente->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $incidente=Incidente::findOrFail($request->id);
        $incidente->estado=$request='0';
        $incidente->save();
    }
}
