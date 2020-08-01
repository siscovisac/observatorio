<template>
    <main class="main">
        <div class="card">
            <div class="card-header text-center bg-primary">
                <strong>SINTESIS DIARIA DE OCURRENCIAS</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-8">
                        <div class="form-row">
                            <div class="input-group col-md-6">
                                <label for="">Filtrar por fecha: </label>
                                <input class="form-control" type="date" title="Buscar desde Fecha" v-model="desdeFecha"> 
                            </div>
                            <div class="input-group col-md-4">
                                <button type="submit" @click="listarRegistro(1)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline-primary" @click="generarReportePDF()"><i class="fa fa-file-pdf"></i> Generar Reporte</button>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm table-responsive-md">
                    <thead>
                        <tr>
                            <th class="text-center">ACCIÓN</th>
                            <th class="text-center" >____FECHA____</th>
                            <th class="text-center" >HORA</th>
                            <th class="text-center" colspan="2">OCURRENCIA</th>
                            <th class="text-center">LUGAR</th>
                            <th class="text-center">SERENAZGO</th>
                            <th class="text-center">RESOLUCIÓN</th>
                            <th class="text-center">USUARIO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="registroOcurrencia in arrayRegistroOcurrencia" :key="registroOcurrencia.id">
                            <td class="text-center">
                                <template v-if="registroOcurrencia.estado">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desactivar" @click="desactivar(registroOcurrencia.id)">
                                        <i class="fa fa-check"> A</i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Activar" @click="activar(registroOcurrencia.id)">
                                        <i class="fa fa-trash-alt"> D</i>
                                    </button>
                                </template>
                            </td>
                            <td>{{ formatoFecha(registroOcurrencia.fecha) }}</td>
                            <td>{{ registroOcurrencia.hora }}</td>
                            <td>
                                <template v-if="registroOcurrencia.inicio==='PATRULLAJE'">
                                    <i class="fa fa-taxi"></i>
                                </template>
                                <template v-else-if="registroOcurrencia.inicio==='VIDEO VIGILANCIA'">
                                    <i class="fa fa-video"></i>
                                </template>
                                <template v-else-if="registroOcurrencia.inicio==='LLAMADA'">
                                    <i class="fa fa-phone"></i>
                                </template>
                                <template v-else>
                                    <i class="fa fa-comment-dots"></i>    
                                </template>
                            </td>
                            <td>
                                <div class="input-group">
                                    <strong><u>{{registroOcurrencia.tipoIncidente_nombre}}</u></strong> &nbsp;
                                    {{ registroOcurrencia.ocurrencia }}
                                </div>
                            </td>
                            <td>{{ registroOcurrencia.zona_nombre +" - "+registroOcurrencia.ubicacion_nombre }}</td>
                            <td>
                                <div class="input-group">
                                    <div v-for="camioneta in registroOcurrencia.unidadMovil" :key="camioneta.id">
                                        <li><u>{{ camioneta.unidad_movil }}</u></li>
                                    </div>
                                    <div v-for="apel in registroOcurrencia.personalServicio" :key="apel.id">
                                        <li>{{ apel.apellidos }}</li>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ registroOcurrencia.solucion }}
                                <template v-if="registroOcurrencia.entidad_id>1">
                                    SE DERIVÓ A {{registroOcurrencia.entidad_nombre}}
                                </template>
                            </td>
                            <td class="text-center">
                                <template v-if="registroOcurrencia.fin">
                                    <a class="badge badge-primary text-white">{{ registroOcurrencia.usuario_nombre }}</a>
                                </template>
                                <template v-else>
                                    <a class="badge badge-danger text-white">{{ registroOcurrencia.usuario_nombre }}</a>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Anterior</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </main>
</template>

<script>
    import moment from 'moment'
    moment.locale('es');

    export default {
        data (){
            return {
                registroOcurrencia_id: 0,
                fecha : null,
                hora : null,
                inicio :'',
                telefono:'',
                administrado:'',
                ocurrencia:'',
                incidente_id:'',
                tipoIncidente_id:'',
                zona_id:'',
                ubicacion_id:'',
                referencia:'',
                unidadMovil_id:'',
                personalServicio:'',
                observacion:'',

                tipoIncidente_nombre:'',
                zona_nombre:'',
                ubicacion_nombre:'',
                unidadMovil_nombre:'',
                leyenda:'',
                nombreParte:'',
                mensajeErrorFoto:0,
                muestraMsgErrorFoto:[],
                nombreFoto:'',

                arrayIncidente : [],
                arrayTipoIncidente : [],
                arrayZona:[],
                arrayUbicacion:[],
                arrayUnidadMovil:[],
                arrayFuenteImagen:[],
                arrayPersonalServicio:[],
                arrayRegistroOcurrencia:[],
                arrayPanelFotografico:[],
                arrayParteServicio:[],
                errorCabecera:[],

                tituloModal : '',
                tipoAccion : 0,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0
                },
                offset : 3,
                desdeFecha : '',
            }
        },
        
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  
                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },

        methods : {
            fechaActual(){
                var fechaMoment=moment().format('YYYY-MM-DD')
                this.fecha=fechaMoment;
            },
            horaActual(){
                var horaMoment=moment().format('HH:mm')
                this.hora=horaMoment;
            },

            formatoFecha: function(d){
                return moment(d).format('ddd DD-MMM-YY');
            },

            listarRegistro(page){
                let me=this;
                axios.get('/listarSintesis',{
                        params: {
                            'page': page,
                            desdeFecha: this.desdeFecha,
                        }
                    }).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRegistroOcurrencia = respuesta.registroOcurrencia.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            desactivar(id) {
                swal("DESACTIVAR!", "Desea desactivar el registro?", "error", {buttons:true, dangerMode: true})
                .then(confirma => {
                    if (confirma) {
                        let me=this;
                        let paginaActual=this.isActived;
                        axios.put("/registroOcurrencia/desactivar", { id: id }).then(function(response) {
                            me.listarRegistro(paginaActual, "", "");
                        });
                    } else {
                        swal.close();
                    }
                });
            },

            activar(id) {
                swal("ACTIVAR!", "Desea activar el registro?", "info", {buttons:true, dangerMode: false})
                .then(confirma => {
                if (confirma) {
                    let me=this;
                    let paginaActual=this.isActived;
                    axios.put("/registroOcurrencia/activar", { id: id }).then(function(response) {
                    me.listarRegistro(paginaActual, "", "");
                    });
                } else {
                    swal.close();
                }
                });
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarRegistro(page,buscar,criterio);
            },

            generarReportePDF(){
                if (this.desdeFecha=="") {
                    swal("FECHA VACÍA!", "Ingrese una fecha válida, por favor", "info")
                }
                else{
                    window.open('/sintesisDiariaPDF/'+this.desdeFecha,'_blank');
                }
            }
        },
        mounted() {
            this.listarRegistro(1,this.buscar,this.criterio);
        }
    }
</script>