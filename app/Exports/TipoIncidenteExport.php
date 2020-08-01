<?php

namespace App\Exports;

use App\TipoIncidente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TipoIncidenteExport implements FromView,ShouldAutoSize
{
    public function view(): View
    {
        return view('listaExportarExcel.tipoIncidentesExcel', [
            'tipoIncidentes' => TipoIncidente::join('genericos','incidentes.genericos_id','=','genericos.id')
            ->join('incidentes','tipo_incidentes.incidente_id','=','incidentes.id')
            ->select('tipo_incidentes.*','genericos.nombre as generico','incidentes.id as incidente_id','incidentes.nombre as incidente_nombre','incidentes.descripcion as generico')->get()
        ]);
    }
}
