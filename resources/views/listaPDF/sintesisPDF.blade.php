<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/estilosPDF.css">
    <title>Sintesis Diaria de Ocurrencias</title>
</head>

<body>
    @foreach($organizacion as $org)
    <div id="header">
        <table>
            <tr>
                <td><img src="{{ $org->logo }}" alt="logotipo de la organización" class="rounded mx-auto" width="140px"></td>
                <td width="450px" class="centro">
                    {{$org->nombre}} <br>
                    {{$org->encabezadoPagina}}
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div class="titulo"><strong>SÍNTESIS DIARIA DE ACTIVIDADES E INTERVENCIONES DE SERENAZGO 
        (FECHA: {{\Carbon\Carbon::parse($desdeFecha)->format('d/m/Y')}})
        </strong>
    </div>
    <br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>HORA</th>
                <th>OCURRENCIA</th>
                <th>LUGAR</th>
                <th>SERENAZGO</th>
                <th>RESOLUCIÓN</th>
                <th>USUARIO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registroOcurrencia as $sintesis)
            <tr>
                <td width="30px">{{ $sintesis->hora }}</td>
                <td width="250px">
                    <strong>{{$sintesis->tipoIncidente_nombre}}:</strong>
                    <div>{{ $sintesis->ocurrencia }}</div>
                </td>
                <td width="80px">{{ $sintesis->zona_nombre}} - {{$sintesis->ubicacion_nombre }} - {{$sintesis->referencia }}</td>
                <td width="150px">
                @if(empty($sintesis->unidadMovil))
                ---
                @else
                    @foreach($sintesis->unidadMovil as $ep=>$det)
                    <u>{{ $det['unidad_movil'] }}</u>
                    @endforeach
                    <br>
                @endif
                @if(empty($sintesis->personalServicio))
                ---
                @else
                    @foreach($sintesis->personalServicio as $ep=>$det)
                    <br>{{ $det['apellidos'] }}
                    @endforeach
                @endif
                </td>
                <td width="250px">
                    {{ $sintesis->solucion }}
                    @if($sintesis->asume>1)
                        , SE DERIVÓ A: {{$sintesis->entidad_nombre}}
                    @endif
                </td>
                <td width="40px">{{ $sintesis->usuario_nombre }}</td>
            </tr>
            @endforeach
            <tr><td colspan="4"></td>
                <td colspan="2" class="cantidad">TOTAL: {{$cantidad}} REGISTROS</td> </tr>
        </tbody>
    </table>

    <div id="footer" >
        <table>
            <tr>
                <td class="centro" width="600px">{{$org->piePagina}}</td>
                <td class="derecha">{{now()}}</td>
            </tr>
        </table>
    </div>
    @endforeach
    <script type="text/php">
        if (isset($pdf)) {
            $font=$fontMetrics->getFont("Arial", "bold");
            $pdf->page_text(760, 20, "Pág. {PAGE_NUM} de {PAGE_COUNT}", $font, 9, array(0, 0, 0));
        }
    </script>
</body>

</html>