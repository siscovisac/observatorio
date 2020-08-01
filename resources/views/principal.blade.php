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
<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    <div id="appPrincipal">
            @if(Auth::check())
                @if(Auth::user()->rol_id==1)
                    @include('principal.headerAdministrador')
                    <div class="app-body"> 
                        @include('principal.sidebarAdministrador')
                        @include('principal.asideAdministrador')
                    </div>
                @elseif(Auth::user()->rol_id==2)
                    @include('principal.headerAdministrador')
                    <div class="app-body">
                        @include('principal.sidebarAsistente')
                        @include('principal.asideAsistente')
                    </div>
                @elseif(Auth::user()->rol_id==3)
                    @include('principal.headerInvitado')
                    <div class="app-body">
                        @include('principal.sidebarInvitado')
                    </div>
                @else
                @endif
                @yield('templatePrincipal')
            @endif
    </div>
    @include('principal.footer')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>