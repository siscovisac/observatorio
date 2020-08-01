<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>OBSERVATORIO MUNICIPAL</title>
    <link rel="shortcut icon" href="img/favicon.jpg">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="app header-fixed sidebar-fixed sidebar-lg-show">
    <div id="appPrincipal">
        @include('entrada.header')
        <div class="app-body">
            @include('entrada.sidebar')
            @yield('templateEntrada')
        </div>
    </div>
    @include('entrada.footer')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>