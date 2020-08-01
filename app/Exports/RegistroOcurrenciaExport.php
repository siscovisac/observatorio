<?php

namespace App\Exports;

use App\RegistroOcurrencia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class RegistroOcurrenciaExport implements FromView,ShouldAutoSize
{
    use Exportable;
    public function __construct($desdeFecha, $hastaFecha)
    {
        $this->desdeFecha = $desdeFecha;
        $this->hastaFecha = $hastaFecha;
    }

    public function view(): View
    {
        return view('listaExportarExcel.registroOcurrenciaExcel', [
            'registroOcurrencia' => RegistroOcurrencia::join('tipo_incidentes','registro_ocurrencias.tipoIncidente_id','=','tipo_incidentes.id')
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
            ->where('registro_ocurrencias.estado','=','1')
            ->whereBetween('registro_ocurrencias.fecha',[$this->desdeFecha, $this->hastaFecha])
            ->orderBy('registro_ocurrencias.fecha', 'asc')
            ->get()
        ]);
    }

}
