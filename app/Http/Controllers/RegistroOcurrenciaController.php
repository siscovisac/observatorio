<?php

namespace App\Http\Controllers;

use App\RegistroOcurrencia;
use App\PanelFotografico;
use App\ParteServicio;
use App\DetallePersonal;
use App\DetalleMovil;
use App\Organizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use App\Exports\RegistroOcurrenciaExport;
use Maatwebsite\Excel\Facades\Excel;

class RegistroOcurrenciaController extends Controller
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
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($desdeFecha==''){
            $registroOcurrencia = RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
                ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
                ->join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
                ->join('zonas','ubicacions.zona_id','=','zonas.id')
                ->join('fuente_imagens','registro_ocurrencias.fuenteImagen_id','=','fuente_imagens.id')
                ->join('entidads','registro_ocurrencias.entidad_id','=','entidads.id')
                ->join('users','registro_ocurrencias.user_id','=','users.id')
                ->select('registro_ocurrencias.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre',
                'tipo_incidentes.id as tipoIncidente_id','tipo_incidentes.nombre as tipoIncidente_nombre',
                'zonas.id as zona_id','zonas.nombre as zona_nombre',
                'ubicacions.id as ubicacion_id','ubicacions.nombre as ubicacion_nombre',
                'entidads.id as entidad_id','entidads.nombre as entidad_nombre',
                'fuente_imagens.id as fuenteImagen_id','fuente_imagens.nombre as fuenteImagen_nombre', 'fuente_imagens.abreveatura',
                'users.name as usuario_nombre')
                ->orderBy('registro_ocurrencias.id', 'desc')->paginate(20);
                // ->orderBy('registro_ocurrencias.fecha', 'desc')->orderBy('registro_ocurrencias.hora', 'desc')->paginate(100);
        }
        else{
            $registroOcurrencia = RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
                ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
                ->join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
                ->join('zonas','ubicacions.zona_id','=','zonas.id')
                ->join('fuente_imagens','registro_ocurrencias.fuenteImagen_id','=','fuente_imagens.id')
                ->join('entidads','registro_ocurrencias.entidad_id','=','entidads.id')
                ->join('users','registro_ocurrencias.user_id','=','users.id')
                ->select('registro_ocurrencias.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre',
                'tipo_incidentes.id as tipoIncidente_id','tipo_incidentes.nombre as tipoIncidente_nombre',
                'zonas.id as zona_id','zonas.nombre as zona_nombre',
                'ubicacions.id as ubicacion_id','ubicacions.nombre as ubicacion_nombre',
                'entidads.id as entidad_id','entidads.nombre as entidad_nombre',
                'fuente_imagens.id as fuenteImagen_id','fuente_imagens.nombre as fuenteImagen_nombre', 'fuente_imagens.abreveatura',
                'users.name as usuario_nombre')
                ->whereBetween('fecha', [$desdeFecha, $hastaFecha])
                ->where($criterio, 'like', '%'. $buscar . '%')
                ->where('users.id','=',1)
                ->orderBy('registro_ocurrencias.fecha', 'desc')->orderBy('registro_ocurrencias.hora', 'desc')->paginate(20);
        }
       return [
            'pagination' => [
                'total'        => $registroOcurrencia->total(),
                'current_page' => $registroOcurrencia->currentPage(),
                'per_page'     => $registroOcurrencia->perPage(),
                'last_page'    => $registroOcurrencia->lastPage(),
                'from'         => $registroOcurrencia->firstItem(),
                'to'           => $registroOcurrencia->lastItem(),
            ],
            'registroOcurrencia' => $registroOcurrencia
        ];
    }

    public function ultimaOcurrencia(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $desdeFecha = $request->desdeFecha;
        $hastaFecha = $request->hastaFecha;
        $buscar = $request->buscar;
        
        if ($buscar==''){
            $registroOcurrencia = RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
                ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
                ->join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
                ->join('zonas','ubicacions.zona_id','=','zonas.id')
                ->join('fuente_imagens','registro_ocurrencias.fuenteImagen_id','=','fuente_imagens.id')
                ->join('users','registro_ocurrencias.user_id','=','users.id')
                ->select('registro_ocurrencias.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre',
                'tipo_incidentes.id as tipoIncidente_id','tipo_incidentes.nombre as tipoIncidente_nombre',
                'zonas.id as zona_id','zonas.nombre as zona_nombre',
                'ubicacions.id as ubicacion_id','ubicacions.nombre as ubicacion_nombre',
                'fuente_imagens.id as fuenteImagen_id','fuente_imagens.nombre as fuenteImagen_nombre', 'fuente_imagens.abreveatura',
                'users.name as usuario_nombre')
                ->orderBy('registro_ocurrencias.fecha', 'desc')->paginate(15);
        }
        else{
            $registroOcurrencia = RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
                ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
                ->join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
                ->join('zonas','ubicacions.zona_id','=','zonas.id')
                ->join('fuente_imagens','registro_ocurrencias.fuenteImagen_id','=','fuente_imagens.id')
                ->join('users','registro_ocurrencias.user_id','=','users.id')
                ->select('registro_ocurrencias.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre',
                'tipo_incidentes.id as tipoIncidente_id','tipo_incidentes.nombre as tipoIncidente_nombre',
                'zonas.id as zona_id','zonas.nombre as zona_nombre',
                'ubicacions.id as ubicacion_id','ubicacions.nombre as ubicacion_nombre',
                'fuente_imagens.id as fuenteImagen_id','fuente_imagens.nombre as fuenteImagen_nombre', 'fuente_imagens.abreveatura',
                'users.name as usuario_nombre')
                ->whereBetween('fecha', [$desdeFecha, $hastaFecha])
                ->where('tipo_incidentes.incidente_id','=', $buscar)
                ->orderBy('registro_ocurrencias.fecha', 'desc')->paginate(15);
        }
       return [
            'pagination' => [
                'total'        => $registroOcurrencia->total(),
                'current_page' => $registroOcurrencia->currentPage(),
                'per_page'     => $registroOcurrencia->perPage(),
                'last_page'    => $registroOcurrencia->lastPage(),
                'from'         => $registroOcurrencia->firstItem(),
                'to'           => $registroOcurrencia->lastItem(),
            ],
            'registroOcurrencia' => $registroOcurrencia
        ];
    }

    public function sintesisDiaria(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $desdeFecha = $request->desdeFecha;
        
        if ($desdeFecha==''){
            $registroOcurrencia = RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
                ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
                ->join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
                ->join('zonas','ubicacions.zona_id','=','zonas.id')
                ->join('entidads','registro_ocurrencias.entidad_id','=','entidads.id')
                ->join('fuente_imagens','registro_ocurrencias.fuenteImagen_id','=','fuente_imagens.id')
                ->join('users','registro_ocurrencias.user_id','=','users.id')
                ->select('registro_ocurrencias.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre',
                'tipo_incidentes.id as tipoIncidente_id','tipo_incidentes.nombre as tipoIncidente_nombre',
                'zonas.id as zona_id','zonas.nombre as zona_nombre',
                'entidads.id as entidad_id','entidads.nombre as entidad_nombre',
                'ubicacions.id as ubicacion_id','ubicacions.nombre as ubicacion_nombre',
                'fuente_imagens.id as fuenteImagen_id','fuente_imagens.nombre as fuenteImagen_nombre', 'fuente_imagens.abreveatura',
                'users.name as usuario_nombre')
                ->where('registro_ocurrencias.estado','=','1')
                ->orderBy('registro_ocurrencias.id', 'desc')->paginate(20);
        }
        else{
            $registroOcurrencia = RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
                ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
                ->join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
                ->join('zonas','ubicacions.zona_id','=','zonas.id')
                ->join('entidads','registro_ocurrencias.entidad_id','=','entidads.id')
                ->join('fuente_imagens','registro_ocurrencias.fuenteImagen_id','=','fuente_imagens.id')
                ->join('users','registro_ocurrencias.user_id','=','users.id')
                ->select('registro_ocurrencias.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre',
                'tipo_incidentes.id as tipoIncidente_id','tipo_incidentes.nombre as tipoIncidente_nombre',
                'zonas.id as zona_id','zonas.nombre as zona_nombre',
                'ubicacions.id as ubicacion_id','ubicacions.nombre as ubicacion_nombre',
                'entidads.id as entidad_id','entidads.nombre as entidad_nombre',
                'fuente_imagens.id as fuenteImagen_id','fuente_imagens.nombre as fuenteImagen_nombre', 'fuente_imagens.abreveatura',
                'users.name as usuario_nombre')
                ->whereDate('fecha', $desdeFecha)
                ->where('registro_ocurrencias.estado','=','1')
                ->orderBy('registro_ocurrencias.fecha', 'asc')->orderBy('registro_ocurrencias.hora', 'asc')->paginate(20);
        }
       return [
            'pagination' => [
                'total'        => $registroOcurrencia->total(),
                'current_page' => $registroOcurrencia->currentPage(),
                'per_page'     => $registroOcurrencia->perPage(),
                'last_page'    => $registroOcurrencia->lastPage(),
                'from'         => $registroOcurrencia->firstItem(),
                'to'           => $registroOcurrencia->lastItem(),
            ],
            'registroOcurrencia' => $registroOcurrencia
        ];
    }

    public function sintesisDiariaPDF(Request $request, $desdeFecha){
        $registroOcurrencia = RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
                ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
                ->join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
                ->join('zonas','ubicacions.zona_id','=','zonas.id')
                ->join('entidads','registro_ocurrencias.entidad_id','=','entidads.id')
                ->join('fuente_imagens','registro_ocurrencias.fuenteImagen_id','=','fuente_imagens.id')
                ->join('users','registro_ocurrencias.user_id','=','users.id')
                ->select('registro_ocurrencias.*','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre',
                'tipo_incidentes.id as tipoIncidente_id','tipo_incidentes.nombre as tipoIncidente_nombre',
                'zonas.id as zona_id','zonas.nombre as zona_nombre',
                'ubicacions.id as ubicacion_id','ubicacions.nombre as ubicacion_nombre',
                'entidads.id as entidad_id','entidads.nombre as entidad_nombre',
                'fuente_imagens.id as fuenteImagen_id','fuente_imagens.nombre as fuenteImagen_nombre', 'fuente_imagens.abreveatura',
                'users.name as usuario_nombre')
                ->whereDate('fecha', $desdeFecha)
                ->where('registro_ocurrencias.estado','=','1')
                ->orderBy('registro_ocurrencias.hora', 'asc')->get();
        $cantidad=count($registroOcurrencia);
        $organizacion = Organizacion::get();
        $pdf=PDF::loadView('listaPDF.sintesisPDF', ['registroOcurrencia'=>$registroOcurrencia, 'desdeFecha'=>$desdeFecha,'cantidad'=>$cantidad, 'organizacion'=>$organizacion]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Sintesis_Diaria_'.$desdeFecha.'.pdf');
   }

    public function detalleUltimaOcurrencia(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $panelFotografico=RegistroOcurrencia::join()
        ->where('registroOcurrencia_id', '=', $request->registroOcurrencia_id);
        return ['panelFotografico'=>$panelFotografico, 'parteServicio'=>$parteServicio];
    }

    public function descargarRegistroOcurrenciasExcel($desdeFecha, $hastaFecha){
       return (new RegistroOcurrenciaExport($desdeFecha,  $hastaFecha))->download('Registro de Ocurrencias - '.$desdeFecha.'_'.$hastaFecha.'.xlsx');
    }

    public function resumenZonas(Request $request){
        if (!$request->ajax()) return redirect('/');
        $desdeFecha = $request->desdeFecha;
        $hastaFecha = $request->hastaFecha;
        $resumenZonas=RegistroOcurrencia::join('ubicacions','registro_ocurrencias.ubicacion_id','=','ubicacions.id')
        ->join('zonas','ubicacions.zona_id','=','zonas.id')
        ->join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
        ->select('zonas.id as zona_id','zonas.nombre as zona_nombre',
                DB::raw('count(*) as total_zonas'))
        ->where('registro_ocurrencias.estado','=',1)
        ->whereBetween('fecha', [$desdeFecha, $hastaFecha]) 
        ->groupBy('zonas.id')
        ->orderBy('total_zonas','desc')->get(); 
        return ['resumenZonas'=>$resumenZonas];
    }
    
    public function resumenGenericos(Request $request){
        if (!$request->ajax()) return redirect('/');
        $desdeFecha = $request->desdeFecha;
        $hastaFecha = $request->hastaFecha;
        $resumenGenericos=RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
        ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
        ->join('genericos','incidentes.generico_id','=','genericos.id')
        ->select('genericos.id as generico_id','genericos.nombre as generico',
                DB::raw('count(*) as total_generico'))
        ->where('registro_ocurrencias.estado','=',1)
        ->whereBetween('fecha', [$desdeFecha, $hastaFecha]) 
        ->groupBy('genericos.id')
        ->get(); 
        return ['resumenGenericos'=>$resumenGenericos];
    }

    public function resumenEventos(Request $request){
        if (!$request->ajax()) return redirect('/');
        $desdeFecha = $request->desdeFecha;
        $hastaFecha = $request->hastaFecha;
        $registroOcurrencia=RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
        ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
        ->join('genericos','incidentes.generico_id','=','genericos.id')
        ->select('incidentes.id as incidente_id','genericos.nombre as generico','incidentes.nombre as incidente_nombre',DB::raw('count(registro_ocurrencias.tipoIncidente_id) as cantidad'))
        ->whereBetween('fecha', [$desdeFecha, $hastaFecha])
        ->where('registro_ocurrencias.estado','=',1)
        ->groupBy('incidentes.id')
        ->orderBy('cantidad','desc')
        ->get();
        return ['registroOcurrencia'=>$registroOcurrencia];
    }
    
    public function resumenModalidad(Request $request){
        if (!$request->ajax()) return redirect('/');
        $desdeFecha = $request->desdeFecha;
        $hastaFecha = $request->hastaFecha;
        $resumenModalidad=RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
        ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
        ->join('genericos','incidentes.generico_id','=','genericos.id')
        ->select('tipo_incidentes.id as tipoIncidente_id','genericos.nombre as generico','incidentes.nombre as evento',
                'tipo_incidentes.nombre as modalidad',DB::raw('count(registro_ocurrencias.tipoIncidente_id) as totalModalidad'))
        ->whereBetween('fecha', [$desdeFecha, $hastaFecha])
        ->where('registro_ocurrencias.estado','=',1)
        ->groupBy('tipo_incidentes.id')
        // ->orderBy('totalModalidad','desc')
        ->orderBy('genericos.id','asc')
        ->get();
        return ['resumenModalidad'=>$resumenModalidad];
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
            'tipoIncidente_id'=>'bail|required|unique_with:registro_ocurrencias,fecha,hora',
            'fecha'=>'bail|date|required',
            'hora'=>'bail|required'
        ],['tipoIncidente_id.unique_with'=>'Esta fecha, hora y tipo de incidente ya ha sido registrado']);
        try {
            DB::beginTransaction();
            $registroOcurrencia=new RegistroOcurrencia();
            $registroOcurrencia->fecha=$request->fecha;
            $registroOcurrencia->hora=$request->hora;
            $registroOcurrencia->inicio=$request->inicio;
            $registroOcurrencia->telefono=$request->telefono;
            $registroOcurrencia->administrado=$request->administrado;
            $registroOcurrencia->ocurrencia=$request->ocurrencia;
            $registroOcurrencia->tipoIncidente_id=$request->tipoIncidente_id;
            $registroOcurrencia->ubicacion_id=$request->ubicacion_id;
            $registroOcurrencia->referencia=$request->referencia;
            $registroOcurrencia->unidadMovil=$request->unidadMovil;
            $registroOcurrencia->personalServicio=$request->personalServicio;
            $registroOcurrencia->interviene=$request->interviene;
            $registroOcurrencia->parteServicio=$request->parteServicio;
            $registroOcurrencia->llamada=$request->llamada;
            $registroOcurrencia->detalleParteServicio=$request->detalleParteServicio;
            $registroOcurrencia->entidad_id=$request->entidad_id;
            $registroOcurrencia->asume=$request->asume;
            $registroOcurrencia->fuenteImagen_id=$request->fuenteImagen_id;
            $registroOcurrencia->fin=$request->fin;
            $registroOcurrencia->solucion=$request->solucion;
            $registroOcurrencia->user_id=\Auth::user()->id;
            $registroOcurrencia->estado=1;
            $registroOcurrencia->save();

            $detalleps = $request->personalServicio;
            $detallem = $request->unidadMovil;
            if (!empty($detalleps)) {
                foreach($detalleps as $ep=>$deta)
                {
                    $detallep = new DetallePersonal();
                    $detallep->registroOcurrencia_id = $registroOcurrencia->id;
                    $detallep->personal_id = $deta['id'];
                    $detallep->save();
                }
            }
            if (!empty($detallem)) {
                foreach($detallem as $ep=>$det)
                {
                    $detallev = new DetalleMovil();
                    $detallev->registroOcurrencia_id = $registroOcurrencia->id;
                    $detallev->movil_id = $det['id'];
                    $detallev->save();
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RegistroOcurrencia  $registroOcurrencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RegistroOcurrencia $registroOcurrencia)
    {
        if (!$request->ajax()) return redirect('/');
        $request->validate([
            'tipoIncidente_id'=>'required|unique_with:registro_ocurrencias,fecha,hora,'.request()->get("id"),
            'fecha'=>'bail|date|required',
            'hora'=>'bail|required'
        ],['tipoIncidente_id.unique_with'=>'Esta fecha, hora y tipo de incidencia ya fue registrado, no se puede actualizar.']);
        $registroOcurrencia = RegistroOcurrencia::findOrFail($request->id);
        $registroOcurrencia->fecha=$request->fecha;
        $registroOcurrencia->hora=$request->hora;
        $registroOcurrencia->inicio=$request->inicio;
        $registroOcurrencia->telefono=$request->telefono;
        $registroOcurrencia->administrado=$request->administrado;
        $registroOcurrencia->ocurrencia=$request->ocurrencia;
        $registroOcurrencia->tipoIncidente_id=$request->tipoIncidente_id;
        $registroOcurrencia->ubicacion_id=$request->ubicacion_id;
        $registroOcurrencia->referencia=$request->referencia;
        $registroOcurrencia->unidadMovil=$request->unidadMovil;
        $registroOcurrencia->personalServicio=$request->personalServicio;
        $registroOcurrencia->interviene=$request->interviene;
        $registroOcurrencia->parteServicio=$request->parteServicio;
        $registroOcurrencia->llamada=$request->llamada;
        $registroOcurrencia->detalleParteServicio=$request->detalleParteServicio;
        $registroOcurrencia->entidad_id=$request->entidad_id;
        $registroOcurrencia->asume=$request->asume;
        $registroOcurrencia->fuenteImagen_id=$request->fuenteImagen_id;
        $registroOcurrencia->fin=$request->fin;
        $registroOcurrencia->solucion=$request->solucion;
        $registroOcurrencia->user_id=\Auth::user()->id;
        $registroOcurrencia->estado=1;
        $registroOcurrencia->save();
    }
    
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $registroOcurrencia = RegistroOcurrencia::findOrFail($request->id);
        $registroOcurrencia->user_id=\Auth::user()->id;
        $registroOcurrencia->estado=1;
        $registroOcurrencia->save();
    }
    
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $registroOcurrencia = RegistroOcurrencia::findOrFail($request->id);
        $registroOcurrencia->user_id=\Auth::user()->id;
        $registroOcurrencia->estado=0;
        $registroOcurrencia->save();
    }

}
