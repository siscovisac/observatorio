<?php

namespace App\Http\Controllers;

use App\TipoIncidente;
use Illuminate\Http\Request;
use App\Organizacion;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\TipoIncidenteExport;
use Maatwebsite\Excel\Facades\Excel;

class TipoIncidenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $tipoIncidente = TipoIncidente::join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
            ->select('tipo_incidentes.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre')
            ->orderBy('incidentes.nombre', 'asc')->paginate(10);
        }
        else{
            $tipoIncidente = TipoIncidente::join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
            ->select('tipo_incidentes.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre')
            ->where('tipo_incidentes.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('incidentes.nombre', 'asc')->paginate(10);
        }
       return [
            'pagination' => [
                'total'        => $tipoIncidente->total(),
                'current_page' => $tipoIncidente->currentPage(),
                'per_page'     => $tipoIncidente->perPage(),
                'last_page'    => $tipoIncidente->lastPage(),
                'from'         => $tipoIncidente->firstItem(),
                'to'           => $tipoIncidente->lastItem(),
            ],
            'tipoIncidente' => $tipoIncidente
        ]; 
    }

    public function selectTipoIncidente(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $criterio = $request->incidente_id;
        $tipoIncidente=TipoIncidente::where('incidente_id','=',$criterio)->where('estado','=',1)->orderBy('nombre','ASC')->get();
        return ['tipoIncidente' => $tipoIncidente];
    }

    public function listarTipoIncidentePDF(){
        $tipoIncidentes = TipoIncidente::join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
        ->join('genericos','incidentes.generico_id','=','genericos.id')
        ->select('tipo_incidentes.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre', 'incidentes.descripcion as generico', 'genericos.nombre as generico')
        ->orderBy('tipo_incidentes.id')->get();
        $cantidad=count($tipoIncidentes);
        $organizacion = Organizacion::get();
        $pdf=PDF::loadView('listaPDF.tipoIncidentesPDF', compact(['tipoIncidentes','cantidad','organizacion']));
        $pdf->setPaper('A4', '');
        // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Lista-incidencias.pdf');
    }

    public function listarTipoIncidenteExcel(){
        return Excel::download(new TipoIncidenteExport, 'Modalidad_Ocurrencia.xlsx');
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
            'nombre' => 'bail|required|min:5|max:50|unique:tipo_incidentes',
            'descripcion'=>'nullable|max:145',
            'ordenanza'=>'nullable|max:145',
            'incidente_id' => 'required|integer|min:1'
        ],
        ['incidente_id.min'=> 'Seleccione una :attribute']);
        $tipoIncidente=new TipoIncidente();
        $tipoIncidente->nombre=$request->nombre;
        $tipoIncidente->descripcion=$request->descripcion;
        $tipoIncidente->ordenanza=$request->ordenanza;
        $tipoIncidente->incidente_id=$request->incidente_id;
        $tipoIncidente->estado=$request=1;
        $tipoIncidente->user_id=\Auth::user()->id;
        $tipoIncidente->save();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoIncidente  $tipoIncidente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoIncidente $tipoIncidente)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'nombre' => ['required', 'max:50', 'unique:tipo_incidentes,nombre,'.request()->get("id")],
            'descripcion'=>'nullable|max:145',
            'ordenanza'=>'nullable|max:145',
            'incidente_id' => 'required|integer|min:1'
        ],
        ['incidente_id.min'=> 'Seleccione una :attribute']);

        $tipoIncidente=TipoIncidente::findOrFail($request->id);
        $tipoIncidente->nombre=$request->nombre;
        $tipoIncidente->descripcion=$request->descripcion;
        $tipoIncidente->ordenanza=$request->ordenanza;
        $tipoIncidente->incidente_id=$request->incidente_id;
        $tipoIncidente->estado=$request=1;
        $tipoIncidente->user_id=\Auth::user()->id;
        $tipoIncidente->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tipoIncidente=TipoIncidente::findOrFail($request->id);
        $tipoIncidente->estado=$request=1;
        $tipoIncidente->user_id=\Auth::user()->id;
        $tipoIncidente->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tipoIncidente=TipoIncidente::findOrFail($request->id);
        $tipoIncidente->estado=$request=0;
        $tipoIncidente->user_id=\Auth::user()->id;
        $tipoIncidente->save();
    }
}
