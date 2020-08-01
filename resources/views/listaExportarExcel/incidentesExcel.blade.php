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
                <th>ITEM</th>
                <th>EVENTOS</th>
                <th>GENERICO</th>
                <th>ESTADO</th>
            </tr>
        </thead>
        <tbody>
        <br>
            @foreach($incidentes as $inc)
            <tr>
                <td>{{$inc->id}}</td>
                <td>{{$inc->nombre}}</td>
                <td>{{$inc->descripcion}}</td>
                <td>{{$inc->estado?'ACTIVO':'INACTIVO'}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>    
</body>
</html>