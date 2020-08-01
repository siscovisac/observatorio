<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BD_APP-SERENAZGO</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>FECHA</th>
                <th>MES</th>
                <th>OCURRENCIA</th>
                <th>EVENTO</th>
                <th>MODALIDAD</th>
                <th>ZONA</th>
                <th>UBICACION</th>
            </tr>
        </thead>
        <tbody>

            @foreach($registroOcurrencia as $ocurrencia)
            <tr>
                <td>{{ $ocurrencia->fecha }}</td>
                <!-- <td>{{ \Carbon\Carbon::parse($ocurrencia->fecha)->formatLocalized('%B') }}</td> -->
                <td>{{ \Carbon\Carbon::parse($ocurrencia->fecha)->format('F') }}</td>
                <td>{{ $ocurrencia->ocurrencia}}</td>
                <td>{{ $ocurrencia->tipoIncidente_nombre}}</td>
                <td>{{ $ocurrencia->incidente_nombre}}</td>
                <td>{{ $ocurrencia->zona_nombre}}</td>
                <td>{{ $ocurrencia->ubicacion_nombre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</body>
</html>