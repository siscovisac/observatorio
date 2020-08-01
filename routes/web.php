<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware'=>['guest']],function(){
    Route::get('/', function () {
        return view('entrada/menuEntrada');
    });
    // Auth::routes();
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    
    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

    // Email Verification Routes...
    if ($options['verify'] ?? false) {
        Route::emailVerification();
    }
});

Route::group(['middleware'=>['auth']],function(){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout'); 
    
    //Rutas según Roles
    Route::group(['middleware'=>['Administrador']],function(){
        Route::get('/generico','GenericoController@index');
        Route::post('/generico/registrar','GenericoController@store');
        Route::put('/generico/actualizar','GenericoController@update');
        Route::put('/generico/activar','GenericoController@activar');
        Route::put('/generico/desactivar','GenericoController@desactivar');
        Route::get('/selectGenerico','GenericoController@selectGenerico');
        
        Route::get('/incidente','IncidenteController@index');
        Route::post('/incidente/registrar','IncidenteController@store');
        Route::put('/incidente/actualizar','IncidenteController@update');
        Route::put('/incidente/activar','IncidenteController@activar');
        Route::put('/incidente/desactivar','IncidenteController@desactivar');
        Route::get('/selectIncidente','IncidenteController@selectIncidente');
        Route::get('/incidentePDF','IncidenteController@listarIncidentePDF');
        Route::get('/incidenteExcel','IncidenteController@listarIncidenteExcel');
        
        Route::get('/tipoIncidente','TipoIncidenteController@index');
        Route::post('/tipoIncidente/registrar','TipoIncidenteController@store');
        Route::put('/tipoIncidente/actualizar','TipoIncidenteController@update');
        Route::put('/tipoIncidente/activar','TipoIncidenteController@activar');
        Route::put('/tipoIncidente/desactivar','TipoIncidenteController@desactivar');
        Route::get('/selectTipoIncidente/{incidente_id}','TipoIncidenteController@selectTipoIncidente');
        Route::get('/tipoIncidentePDF','TipoIncidenteController@listarTipoIncidentePDF');
        Route::get('/tipoIncidenteExcel','TipoIncidenteController@listarTipoIncidenteExcel');
        
        Route::get('/zona','ZonaController@index');
        Route::post('/zona/registrar','ZonaController@store');
        Route::put('/zona/actualizar','ZonaController@update');
        Route::put('/zona/activar','ZonaController@activar');
        Route::put('/zona/desactivar','ZonaController@desactivar');
        Route::get('/selectZona','ZonaController@selectZona');

        Route::get('/ubicacion','UbicacionController@index');
        Route::post('/ubicacion/registrar','UbicacionController@store');
        Route::put('/ubicacion/actualizar','UbicacionController@update');
        Route::put('/ubicacion/activar','UbicacionController@activar');
        Route::put('/ubicacion/desactivar','UbicacionController@desactivar');
        Route::get('/selectUbicacion/{zona_id}','UbicacionController@selectUbicacion');
        
        Route::get('/unidadMovil','UnidadMovilController@index');
        Route::post('/unidadMovil/registrar','UnidadMovilController@store');
        Route::put('/unidadMovil/actualizar','UnidadMovilController@update');
        Route::put('/unidadMovil/activar','UnidadMovilController@activar');
        Route::put('/unidadMovil/desactivar','UnidadMovilController@desactivar');
        Route::get('/selectUnidadMovil/listarUnidadMovil','UnidadMovilController@selectUnidadMovil');
        
        Route::get('/fuenteImagen','FuenteImagenController@index');
        Route::post('/fuenteImagen/registrar','FuenteImagenController@store');
        Route::put('/fuenteImagen/actualizar','FuenteImagenController@update');
        Route::put('/fuenteImagen/activar','FuenteImagenController@activar');
        Route::put('/fuenteImagen/desactivar','FuenteImagenController@desactivar');
        Route::get('/selectFuenteImagen','FuenteImagenController@selectFuenteImagen');

        Route::get('/cargo','CargoController@index');
        Route::post('/cargo/registrar','CargoController@store');
        Route::put('/cargo/actualizar','CargoController@update');
        Route::put('/cargo/activar','CargoController@activar');
        Route::put('/cargo/desactivar','CargoController@desactivar');
        Route::get('/cargo/selectCargo','CargoController@selectCargo');
        
        Route::get('/entidad','EntidadController@index');
        Route::post('/entidad/registrar','EntidadController@store');
        Route::put('/entidad/actualizar','EntidadController@update');
        Route::put('/entidad/activar','EntidadController@activar');
        Route::put('/entidad/desactivar','EntidadController@desactivar');
        Route::get('/entidad/selectEntidad','EntidadController@selectEntidad');
        Route::get('/selectDependenciaEntidad/{origen}','EntidadController@selectEntidadOrigen');

        Route::get('/personal','PersonalServicioController@index');
        Route::post('/personal/registrar','PersonalServicioController@store');
        Route::put('/personal/actualizar','PersonalServicioController@update');
        Route::put('/personal/activar','PersonalServicioController@activar');
        Route::put('/personal/desactivar','PersonalServicioController@desactivar');
        Route::get('personalServicio/listarPersonalServicio','PersonalServicioController@listarPersonalServicio');
        Route::get('listarPersonal','PersonalServicioController@listarPersonal');

        Route::get('/registroOcurrencia','RegistroOcurrenciaController@index');
        Route::get('/registroOcurrencia/listarUltima','RegistroOcurrenciaController@ultimaOcurrencia');
        Route::get('/registroOcurrencia/listarDetalle','RegistroOcurrenciaController@detalleUltimaOcurrencia');
        Route::post('/registroOcurrencia/registrar','RegistroOcurrenciaController@store');
        Route::put('/registroOcurrencia/actualizar','RegistroOcurrenciaController@update');
        Route::put('/registroOcurrencia/activar','RegistroOcurrenciaController@activar');
        Route::put('/registroOcurrencia/desactivar','RegistroOcurrenciaController@desactivar');
        Route::get('/listarSintesis','RegistroOcurrenciaController@sintesisDiaria');
        Route::get('/sintesisDiariaPDF/{desdeFecha}','RegistroOcurrenciaController@sintesisDiariaPDF');
        Route::get('/generarReporteExcel/{desdeFecha}/{hastaFecha}','RegistroOcurrenciaController@descargarRegistroOcurrenciasExcel');
        Route::get('/resumenZonas','RegistroOcurrenciaController@resumenZonas');
        Route::get('/resumenIncidencia','RegistroOcurrenciaController@resumenEventos');
        Route::get('/resumenGenericos','RegistroOcurrenciaController@resumenGenericos');
        Route::get('/resumenModalidad','RegistroOcurrenciaController@resumenModalidad');

        Route::get('/panelFotografico/verPanel','PanelFotograficoController@index');
        Route::post('/panelFotografico/subirPanelFotografico','PanelFotograficoController@store');
        Route::delete('/panelFotografico/eliminarPanel','PanelFotograficoController@destroy');
        
        Route::get('/parteServicio/verParte','ParteServicioController@index');
        Route::post('/parteServicio/subirParteServicio','ParteServicioController@store');
        Route::delete('/parteServicio/eliminarParte','ParteServicioController@destroy');

        Route::get('/rol','RolController@index');
        Route::get('/rol/selectRol','RolController@selectRol');

        Route::get('/user','UserController@index');
        Route::get('/perfil','UserController@getAuthUser');
        Route::put('/perfil/actualizar','UserController@perfilUpdate');
        Route::post('/user/registrar','UserController@store');
        Route::put('/user/actualizar','UserController@update');
        Route::put('/user/activar','UserController@activar');
        Route::put('/user/desactivar','UserController@desactivar');
        
        Route::get('/dashboard','DashboardController@index');

        Route::get('/organizacion','OrganizacionController@index');
        Route::get('/muestraOrganizacion','OrganizacionController@muestraOrganizacion');
        Route::post('/subirLogo','OrganizacionController@update');
        
        Route::get('/resumenRecord','DetallePersonalController@index');
        Route::get('/resumenIntervencion/{desdeFecha}/{hastaFecha}','DetallePersonalController@recordIntervencionPDF');
        Route::get('/incidenteIntervencion','DetallePersonalController@incidenteIntervencion');
        
    });
    
    Route::group(['middleware'=>['AsistenteTecnico']],function(){
        
    });

    Route::group(['middleware'=>['JefeGrupo']],function(){

    });

    Route::group(['middleware'=>['Invitado']],function(){

    });

});

