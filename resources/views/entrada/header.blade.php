<header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#" @click="menu=1">
        <img class="navbar-brand-full" src="img/logoOrganizacion.jpg" width="120" height="40" alt="CoreUI Logo">
        <img class="navbar-brand-minimized" src="img/iconoOrganizacion.jpg" width="40" height="40" alt="CoreUI Logo">
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <ul class="nav navbar-nav ml-auto mr-3">
        @guest
            <li class="nav-item">
                <a class="btn btn-primary btn-sm " href="{{ route('login') }}" data-toggle="tooltip" title="Iniciar sesión">
                    <i class="fa fa-user"></i> Iniciar Sesión
                </a>
            </li>
        @else
            <li class="nav-item">
                <a id="navbarDropdown" class="btn btn-primary btn-sm dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    <i class="fa fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('home') }}">Ir al sistema</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        Cerrar sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest
    </ul>
</header>