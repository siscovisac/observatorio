<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listado_Indentes</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>ID_M</th>
                <th>MODALIDAD</th>
                <th>ID_E</th>
                <th>GENERICO</th>
                <th>EVENTO</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
        <br>
            @foreach($tipoIncidentes as $inc)
            <tr>
                <td>{{$inc->id}}</td>
                <td>{{$inc->nombre}}</td>
                <td>{{$inc->incidente_id}}</td>
                <td>{{$inc->incidente_nombre}}</td>
                <td>{{$inc->generico}}</td>
                <td>{{$inc->estado?'ACTIVO':'INACTIVO'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</body>
</html>