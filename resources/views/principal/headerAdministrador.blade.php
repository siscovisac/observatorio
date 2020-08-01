<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" @click="menu=0" href="#">
        <img class="navbar-brand-full" src="img/logoOrganizacion.jpg" width="100" height="40" alt="CoreUI Logo">
        <img class="navbar-brand-minimized" src="img/iconoOrganizacion.jpg" width="40" height="40" alt="CoreUI Logo">
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item d-md-down-none ml-3" @click="menu=1" data-toggle="tooltip" title="Registro diario de ocurrencias">
            <a class="nav-link  text-primary" href="#">
                <i class="fa fa-clock fa-2x"></i>
                Ocurrencias
            </a>
        </li>
        <li class="nav-item d-md-down-none ml-3" @click="menu=3" data-toggle="tooltip" title="Sintesis Diaria de ocurrencias">
            <a class="nav-link  text-success" href="#">
                <i class="fa fa-check-square fa-2x"></i>
                Sintesis Diaria
            </a>
        </li>
        
    </ul>
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown mr-3">
            <a class="btn btn-ghost-primary btn-sm dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
             <i class="fa fa-user"></i>
             {{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" @click="menu=50" href="#">
                    <i class="fa fa-user"></i> Perfil</a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <i class="fa fa-lock"></i> Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
    <!-- <button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">
        <span class="navbar-toggler-icon"></span>
    </button> -->
</header>