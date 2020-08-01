/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
Vue.component('generico', require('./components/Generico.vue'));
Vue.component('incidente', require('./components/Incidente.vue'));
Vue.component('tipo-incidente', require('./components/TipoIncidente.vue'));
Vue.component('registro-ocurrencia', require('./components/RegistroOcurrencia.vue'));
Vue.component('ultima-ocurrencia', require('./components/UltimaOcurrencia.vue'));
Vue.component('zona', require('./components/Zona.vue'));
Vue.component('ubicacion', require('./components/Ubicacion.vue'));
Vue.component('unidad-movil', require('./components/UnidadMovil.vue'));
Vue.component('fuente-imagen', require('./components/FuenteImagen.vue'));
Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('grafico-estadistico', require('./components/GraficoEstadistico.vue'));

Vue.component('cargo', require('./components/Cargo.vue'));
Vue.component('entidad', require('./components/Entidad.vue'));
Vue.component('personal-servicio', require('./components/Personal.vue'));
Vue.component('sintesis-diaria', require('./components/SintesisDiaria.vue'));

Vue.component('resumen-incidencia', require('./components/ResumenIncidencia.vue'));

Vue.component('rol', require('./components/Rol.vue'));
Vue.component('user', require('./components/User.vue'));
Vue.component('perfil', require('./components/Perfil.vue'));

Vue.component('organizacion', require('./components/Organizacion.vue'));
Vue.component('record-intervenciones', require('./components/ResumenIntervencion.vue'));
Vue.component('record-personal', require('./components/RecordIntervencion.vue'));
Vue.component('reporte-pivote', require('./components/ReportePivote.vue'));

const app = new Vue({
    el: '#appPrincipal',
    data: {
        menu: 1
    }
});