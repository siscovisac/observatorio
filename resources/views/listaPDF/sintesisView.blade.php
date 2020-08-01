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
    <div id="header">
        <img src="{{ asset('img/logotipoMuniChancay.jpg') }}" alt="" width="25%">
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
                <th>FECHA</th>
                <th>OCURRENCIA</th>
                <th>SERENAZGO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registroOcurrencia as $sintesis)
            <tr>
                <td width="100px">{{ $sintesis->fecha }}</td>
                <td width="500px">
                    <strong>{{$sintesis->ocurrencia}}</strong>
                </td>
                <td>
                @foreach($sintesis->unidadMovil as $dt=>$det)
                    {{ $det['unidad_movil'] }}
                @endforeach
                </td>
            </tr>
            @endforeach
            <tr><td colspan="4"></td>
                <td colspan="2" class="cantidad">TOTAL: {{$cantidad}} REGISTROS</td> </tr>
        </tbody>
    </table>

    <div id="footer" class="derecha">
        {{now()}}
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font=$fontMetrics->getFont("Arial", "bold");
            $pdf->page_text(760, 20, "Pág. {PAGE_NUM} de {PAGE_COUNT}", $font, 9, array(0, 0, 0));
        }
    </script>

</body>

</html>