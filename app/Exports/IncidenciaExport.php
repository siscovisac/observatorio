<?php

namespace App\Exports;

use App\Incidente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class IncidenciaExport implements FromView,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    *
    * public function collection()
    *{
    *    return Incidente::select('id','nombre','descripcion','estado')->get();
    *}
    **/
    public function view(): View
    {
        return view('listaExportarExcel.tipoIncidentesExcel', [
            'incidentes' => Incidente::select('id','nombre','descripcion','estado')->get()
        ]);
    }
}
