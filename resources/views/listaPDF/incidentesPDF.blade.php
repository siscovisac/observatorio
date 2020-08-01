<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/estilosPDF.css">
    <title>Lista Incidentes</title>
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
    <div class="titulo"><strong>LISTADO - TIPOS DE INCIDENCIA</strong></div>
    <br/>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ITEM</th>
                <th>EVENTOS</th>
                <th>GENERICO</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incidentes as $inc)
            <tr>
                <td>{{ $inc->id }}</td>
                <td>{{ $inc->nombre }}</td>
                <td>{{ $inc->generico_nombre }}</td>
                <td>{{ $inc->estado?'ACTIVO':'INACTIVO' }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="cantidad">
                    <strong>-- TOTAL: {{ $cantidad}} REGISTROS --</strong>
                </td>
            </tr>
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
            $pdf->page_text(500, 20, "Pág. {PAGE_NUM} de {PAGE_COUNT}", $font, 9, array(0, 0, 0));
        }
    </script>
    
</body>
</html>