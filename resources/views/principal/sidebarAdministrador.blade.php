<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title text-center bg-primary">SISTEMA GESTOR DE INCIDENTES SISGEIN-v.1.2</li>
            <li @click="menu=0" class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fa fa-tachometer-alt"></i>DASHBOARD
                    <span class="badge badge-primary">Nuevo</span>
                </a>
            </li>
            <li @click="menu=1" class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="fa fa-clock"></i>OCURRENCIAS
                </a>
            </li>
           
           
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-chart-bar"></i>ESTADISTICA</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-project-diagram"></i>
                        </span>Reporte Pivote</a>
                    </li>
                    <li @click="menu=4" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-chart-bar"></i>
                            </span>Gráfica Estadístico</a>
                    </li>
                    <li @click="menu=5" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-sort-amount-down"></i>
                            </span>Resumen Ocurrencias</a>
                    </li>
                    <li @click="menu=7" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-handshake"></i>
                            </span>Resumen Intervención</a>
                    </li>
                    <li @click="menu=8" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-trophy"></i>
                            </span>Record Intervención</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-print"></i>REPORTES</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=2" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-bell"></i>Ver Ocurrencias
                        </a>
                    </li>
                    <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-check-square"></i>Síntesis Diaria
                        </a>
                    </li>
                    <li @click="menu=6" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-file-image"></i>
                            </span>Panel Fotográfico</a>
                    </li>
                    
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-cogs"></i>MANTENIMIENTO</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=9" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>Genérico
                        </a>
                    </li>
                    <li @click="menu=10" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>Tipo de Incidencia
                        </a>
                    </li>

                    <li @click="menu=11" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>Sub-Tipo de Incidencia
                        </a>
                    </li>
                    
                    <li @click="menu=12" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>Zona
                        </a>
                    </li>

                    <li @click="menu=13" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>Ubicación
                        </a>
                    </li>

                    <li @click="menu=14" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>Unidad Movil
                        </a>
                    </li>
                    
                    <li @click="menu=15" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>Origen de Imágenes
                        </a>
                    </li>
                    
                    <li @click="menu=16" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>Cargos
                        </a>
                    </li>
                    <li @click="menu=17" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-users"></i>Personal de Servicio
                        </a>
                    </li>
                    
                    <li @click="menu=18" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-home"></i>Entidad
                        </a>
                    </li>

                    <li @click="menu=19" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-hotel"></i>Organizacion
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-user"></i>USUARIOS</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=20" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>
                            </span>Usuarios</a>
                    </li>
                    <li @click="menu=21" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>
                            </span>Roles</a>
                    </li>
                </ul>
            </li>
            <!-- <li class="nav-title text-center bg-primary">SIS. GESTOR DE EXPEDIENTES - MINI-SISGEDO-v.1.0</li>
            <li @click="menu=30" class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="fa fa-file-invoice"></i>REGISTRO EXPEDIENTE
                </a>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="fa fa-file-alt"></i>EXPEDIENTES</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=31" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>
                            </span>En Proceso</a>
                    </li>
                    <li @click="menu=32" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>
                            </span>Procesados/Arch.</a>
                    </li>
                    <li @click="menu=33" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>
                            </span>Por Recibir</a>
                    </li>
                    <li @click="menu=35" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-genderless"></i>Tipo de Expedientes
                        </a>
                    </li>
                </ul>
            </li> -->
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>