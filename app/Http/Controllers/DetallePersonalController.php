<?php

namespace App\Http\Controllers;
use App\Organizacion;
use App\DetallePersonal;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class DetallePersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $desdeFecha = $request->desdeFecha;
        $hastaFecha = $request->hastaFecha;
        $intevencionPersonal=DetallePersonal::join('personal_servicios','detalle_personals.personal_id','=','personal_servicios.id')
            ->join('cargos','personal_servicios.cargo_id','=','cargos.id')
            ->join('registro_ocurrencias','detalle_personals.registroOcurrencia_id','=','registro_ocurrencias.id')
            ->select('personal_servicios.id as personal_id', DB::raw("CONCAT(personal_servicios.apellidos,' ',personal_servicios.nombres) as personal_nombre"),
                    'cargos.nombre as cargo', DB::raw("count(*) as total_intervencion"))
            ->where('registro_ocurrencias.estado','=','1')->where('personal_servicios.estado','=','1')
            ->whereBetween('registro_ocurrencias.fecha', [$desdeFecha, $hastaFecha])
            ->whereNotIn('cargos.id', [1])
            ->groupBy('personal_servicios.id')
            ->orderBy('cargos.id','asc')->orderBy('total_intervencion','desc')->get();
            return ['intevencionPersonal'=>$intevencionPersonal];
    }

    public function recordIntervencionPDF(Request $request, $desdeFecha,$hastaFecha){
        $desdeFecha = $request->desdeFecha;
        $hastaFecha = $request->hastaFecha;
        $intevencionPersonal=DetallePersonal::join('personal_servicios','detalle_personals.personal_id','=','personal_servicios.id')
            ->join('cargos','personal_servicios.cargo_id','=','cargos.id')
            ->join('registro_ocurrencias','detalle_personals.registroOcurrencia_id','=','registro_ocurrencias.id')
            ->select('personal_servicios.id as personal_id', DB::raw("CONCAT(personal_servicios.apellidos,' ',personal_servicios.nombres) as personal_nombre"),
                    'cargos.nombre as cargo', DB::raw("count(*) as total_intervencion"))
            ->where('registro_ocurrencias.estado','=','1')->where('personal_servicios.estado','=','1')
            ->whereBetween('registro_ocurrencias.fecha', [$desdeFecha, $hastaFecha])
            ->whereNotIn('cargos.id', [1])
            ->groupBy('personal_servicios.id')
            ->orderBy('cargos.id','asc')->orderBy('total_intervencion','desc')->get();
        $cantidad=count($intevencionPersonal);
        $organizacion = Organizacion::get();
        $pdf=PDF::loadView('listaPDF.resumenRecordPDF', ['intevencionPersonal'=>$intevencionPersonal, 'desdeFecha'=>$desdeFecha,'hastaFecha'=>$hastaFecha,'cantidad'=>$cantidad, 'organizacion'=>$organizacion]);
        $pdf->setPaper('A4', '');
        return $pdf->stream('Resumen_Intervenciones'.$desdeFecha.'_'.$hastaFecha.'.pdf');
    }

    public function incidenteIntervencion(Request $request){
        if (!$request->ajax()) return redirect('/');
        $desdeFecha = $request->desdeFecha;
        $hastaFecha = $request->hastaFecha;
        $personal = $request->personal_id;
        $intevencionPersonal=DetallePersonal::join('personal_servicios','detalle_personals.personal_id','=','personal_servicios.id')
        ->join('cargos','personal_servicios.cargo_id','=','cargos.id')
        ->join('registro_ocurrencias','detalle_personals.registroOcurrencia_id','=','registro_ocurrencias.id')
        ->join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
        ->join('entidads','registro_ocurrencias.entidad_id','=','entidads.id')
        ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
        ->join('genericos','incidentes.generico_id','=','genericos.id')
        ->join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
        ->join('zonas','ubicacions.zona_id','=','zonas.id')
        ->select('registro_ocurrencias.fecha', 'registro_ocurrencias.hora','registro_ocurrencias.ocurrencia',
                'genericos.nombre as generico', 'incidentes.nombre as evento',
                'tipo_incidentes.nombre as modalidad',
                'zonas.nombre as zona','ubicacions.nombre as ubicacion','entidads.id as entidad_id', 
                'entidads.nombre as entidad_nombre',
                'registro_ocurrencias.solucion',
                'registro_ocurrencias.referencia'
                )
        ->where('registro_ocurrencias.estado','=','1')
        // ->whereIn('genericos.id', [1,4,6])
        ->where('registro_ocurrencias.estado','=','1')
        // ->where('registro_ocurrencias.parteServicio','=','1')
        ->where('personal_servicios.id','=',$personal)
        ->whereBetween('registro_ocurrencias.fecha', [$desdeFecha, $hastaFecha])
        // ->groupBy('registro_ocurrencias.fecha')
        ->orderBy('registro_ocurrencias.fecha','asc')->get();
        $cantidad=count($intevencionPersonal);
        $organizacion = Organizacion::get();
        return ['intevencionPersonal'=>$intevencionPersonal, 'cantidad'=>$cantidad, 'organizacion'=>$organizacion];
   }

   public function incidenteIntervencionPDF(Request $request, $desdeFecha){
    if (!$request->ajax()) return redirect('/');
    $intevencionPersonal=DetallePersonal::join('personal_servicios','detalle_personals.personal_id','=','personal_servicios.id')
        ->join('cargos','personal_servicios.cargo_id','=','cargos.id')
        ->join('registro_ocurrencias','detalle_personals.registroOcurrencia_id','=','registro_ocurrencias.id')
        ->join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
        ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
        ->join('genericos','incidentes.incidente_id','=','genericos.id')
        ->join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
        ->join('zonas','ubicacions.zona_id','=','zonas.id')
        
        ->select('personal_servicios.id as personal_id', 'genericos.nombre as generico', 'incidentes.nombre as incidentes',
                DB::raw("CONCAT(personal_servicios.apellidos,' ',personal_servicios.nombres) as personal_nombre"),
                'cargos.nombre as cargo', DB::raw("count(*) as total_intervencion"), 'registro_ocurrencias.fecha', 'registro_ocurrencias.hora',
                'genericos.nombre as generico', 'incidentes.nombre as evento', 'tipo_incidentes.nombre as modalidad',
                'zonas.nombre as zona', 'ubicacions.nombre as ubicacion')
        ->where('registro_ocurrencias.estado','=','1')->where('personal_servicios.estado','=','1')->where('personal_servicios.id','=','105')
        // ->whereBetween('registro_ocurrencias.fecha', [$desdeFecha, $hastaFecha])
        ->groupBy('personal_servicios.id')
        ->orderBy('cargos.id','asc')->orderBy('total_intervencion','desc')->get();
    $cantidad=count($intevencionPersonal);
    $organizacion = Organizacion::get();
    $pdf=PDF::loadView('listaPDF.resumenRecordPDF', ['intevencionPersonal'=>$intevencionPersonal, 'desdeFecha'=>$desdeFecha,'hastaFecha'=>$hastaFecha,'cantidad'=>$cantidad, 'organizacion'=>$organizacion]);
    $pdf->setPaper('A4', 'landscape');
    return $pdf->stream('Sintesis_Diaria_'.$desdeFecha.'.pdf');
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
     * Display the specified resource.
     *
     * @param  \App\DetallePersonal  $detallePersonal
     * @return \Illuminate\Http\Response
     */
    public function show(DetallePersonal $detallePersonal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetallePersonal  $detallePersonal
     * @return \Illuminate\Http\Response
     */
    public function edit(DetallePersonal $detallePersonal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetallePersonal  $detallePersonal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetallePersonal $detallePersonal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetallePersonal  $detallePersonal
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetallePersonal $detallePersonal)
    {
        //
    }
}
